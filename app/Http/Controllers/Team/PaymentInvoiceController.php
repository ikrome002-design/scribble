<?php

namespace App\Http\Controllers\Team;

use App\Classes\Permission;
use App\Client;
use App\InvoiceItems;
use App\Invoices;
use App\Mail\SendInvoice;
use App\Mail\SendReceipt;
use App\Receipts;
use Illuminate\Support\Facades\Auth;
use App\SMSTransaction;
use Illuminate\Http\Request;
use Nexmo\Message\Callback\Receipt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\MassInvoices;
use App\Http\Controllers\MpesaController;
use App\Helpers\PriceCalculation;
use App\BuyPlan;
use App\Models\ProSubscription;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Calculation\TextData\Format;
use App\Models\ChangePlan;
use App\Models\ProPlan;
use Illuminate\Support\Facades\Log;
use App\Models\TeamPlan;
use App\Models\TeamSubscription;

class PaymentInvoiceController extends Controller
{

    // confirm mpesa stk payment
    public function stkInvoicePayment(Request $request, $invoice_no)
    {
        $date = date("Y-m-d");
        $invoices = Invoices::where(function ($q) use ($invoice_no) {
            $q->where('invoice_no', $invoice_no)->orWhere('mass_invoice_no', $invoice_no);
        });
        $errors = [];

        if ($invoices->count() == 0) {
            return [
                'errors' =>  'The invoice not found.'
            ];
        }
        $client = Client::find($invoices->first()->cl_id);

        Invoices::where('cl_id', $client->id)
            ->where(function ($q) {
                $q->where('status', 'Unpaid')
                    ->orWhere('status', 'Partially Paid');
            })
            ->whereHas('items', function ($q) use ($date, $client) {
                $q->where(function ($q) {
                    $q->where('pro_recurring_date', '!=', null)
                        ->orwhere('team_recurring_date', '!=', null);
                })
                    ->where(function ($q) use ($date, $client) {
                        $q->where(function ($q) use ($date, $client) {
                            $q->where('pro_recurring_date', '!=', $client->plan_recurring_date)
                                ->orWhere('pro_recurring_date', '<', $date);
                        })
                            ->orWhere(function ($q) use ($date, $client) {
                                $q->where('team_recurring_date', '!=', $client->plan_recurring_date)
                                    ->orWhere('team_recurring_date', '<', $date);
                            });
                    });
            })->update(['status' => 'Expired']);

        $to_pay = $invoices->sum('total') - $invoices->sum('total_paid');
        if ($to_pay < 1) {
            $errors[] = 'Amount to pay is less than 1';
        }

        $reject = $invoices->where(function ($q) {
            $q->where('status', 'Paid')->orWhere('status', 'Cancelled')->orWhere('status', 'Expired');
        });

        if ($reject->count() > 0) {
            $errors[] = "Invoice has either been paid, expired or cancelled";
        }

        if (!$request->phone_number) {
            $errors[] = 'You must enter a valid phone';
        }



        if (count($errors) > 0) {
            return [
                'errors' => $errors
            ];
        }

        $payMpesa = new MpesaController();

        if ($to_pay > 150000) {
            $to_pay = 150000;
        }

        $stkPush = $payMpesa->stkSimulation($invoice_no, $client->id, $to_pay, $request->phone_number, 'Payment for Sms', 'Single');

        if (is_array($stkPush)) {
            return [
                'errors' => $stkPush
            ];
        }

        return ["Please check the notification on " . $request->phone_number . " enter the pin to complete the payment. If it fails please use Mpesa Paybill method."];
    }

    //update after payment
    public function updateAfterPayment($invoice)
    {
        $client = Client::find($invoice->cl_id);
        $client->increment('sms_limit', $invoice->sms_limit);
        $client->save();
        $items = InvoiceItems::where('invoice_no', $invoice->invoice_no)->get();
        $date = date('Y-m-d');
        $proPlans = ProPlan::all();
        $teamPlans = TeamPlan::all();
        //if there a plan change
        $plan_item = $items->where('plan_id', '!=', null)->first();
        $check_plan_match = ($plan_item->plan_id ?? null) != $client->plan_id && ($plan_item->plan_id ?? null) != null;
        if ($check_plan_match || ($client->plan_status == 'Inactive' && $plan_item) || ($client->plan_recurring_date < $date && $plan_item)) {
            $team_id = null;
            $team_members = null;
            $pro_ids = [];
            //get ids to be updates
            foreach ($items as $item) {
                if ($item->pro_subscription_id) {
                    $pro_ids[] = $item->pro_subscription_id;
                }
                if ($item->team_subscription_id) {
                    $team__id = $item->team_subscription_id;
                    $team_members = $item->team_members;
                }
            }
            //change plan now             
            if ($plan_item->change_plan_now || ($client->plan_status == 'Inactive' && $plan_item)) {
                $client->plan_recurring_date = Carbon::now()
                    ->addDays($plan_item->billed_days)->format('Y-m-d');
                $client->plan_status = 'Active';
                $client->plan_id =  $plan_item->plan_id;
                $client->billed_frequency =  $plan_item->billed_frequency;
                $client->save();
                $pro_plan = $proPlans->where('plan_id', $client->plan_id)->first();
                $teamPlan = $teamPlans->where('plan_id', $client->plan_id)->first();
                if (count($pro_ids) > 0) {
                    ProSubscription::whereIn('id', $pro_ids)
                        ->update(
                            [
                                'sub_status' => 'Active',
                                'pro_recurring_date' => Carbon::now()->addDays($plan_item->billed_days)->format('Y-m-d'),
                                'pro_plan_id' => $pro_plan->id,
                                'opted_out' => 'No',
                                'opted_out_date' => null,
                            ]
                        );
                }

                if ($team_id) {
                    TeamPlan::where('id', $team_id)
                        ->update(
                            [
                                'sub_status' => 'Active',
                                'team_recurring_date' => Carbon::now()->addDays($plan_item->billed_days)->format('Y-m-d'),
                                'team_plan_id' => $teamPlan->id,
                                'opted_out' => 'No',
                                'opted_out_date' => null,
                                'team_members' => $team_members
                            ]
                        );
                }
            } else {
                //change next billing days;
                $pro_ids = implode(',', $pro_ids);
                $change_plan_date = Carbon::parse($client->plan_recurring_date)->addDay()->format('Y-m-d');
                ChangePlan::upsert(
                    [
                        'cl_id' => $client->id,
                        'change_plan_date' => $change_plan_date,
                        'invoice_no' => $invoice->invoice_no,
                        'pro_ids' => $pro_ids,
                        'plan_id' => $plan_item->plan_id,
                        'billed_days' => $plan_item->billed_days,
                        'billed_frequency' => $plan_item->billed_frequency,
                        'team_id' => $team_id,
                        'team_members' => $team_members
                    ],
                    ['cl_id'],
                    [
                        'change_plan_date',
                        'invoice_no',
                        'pro_ids',
                        'plan_id',
                        'billed_days',
                        'billed_frequency',
                        'team_id',
                        'team_members'
                    ]
                );
            }
        } else {
            foreach ($items as $item) {
                //change pro sub to active
                if ($item->pro_subscription_id) {
                    $pro = ProSubscription::find($item->pro_subscription_id);
                    if ($pro) {
                        //update  active  for pro if the same  plan _reccuring_date 
                        if ($item->pro_recurring_date) {
                            if ($item->pro_recurring_date == $client->plan_recurring_date && $item->pro_recurring_date >= $date) {
                                $pro->pro_recurring_date = $item->pro_recurring_date;
                                $pro->sub_status = 'Active';
                                $pro->opted_out = 'No';
                                $pro->opted_out_date = null;
                                $pro->save();
                            }
                        }
                        //extend pro subscription if has billed days
                        elseif ($item->billed_days) {
                            //extend pro susbcription is is active 
                            if ($pro->pro_recurring_date >= $date) {
                                $next_recurring_date = Carbon::parse($pro->pro_recurring_date)
                                    ->addDays($item->billed_days)->format('Y-m-d');
                                $pro->pro_recurring_date =   $next_recurring_date;
                                $pro->sub_status = 'Active';
                                $pro->opted_out = 'No';
                                $pro->opted_out_date = null;
                                $pro->save();
                            } else {
                                //extend pro susbcription is is Inactive 
                                $next_recurring_date = Carbon::now()
                                    ->addDays($item->billed_days)->format('Y-m-d');
                                $pro->pro_recurring_date =   $next_recurring_date;
                                $pro->sub_status = 'Active';
                                $pro->opted_out = 'No';
                                $pro->opted_out_date = null;
                                $pro->save();
                            }
                        }
                    }
                }

                //update main suscription
                if ($item->plan_id) {
                    //extend if active
                    if ($client->plan_recurring_date >= $date) {
                        $next_recurring_date = Carbon::parse($client->plan_recurring_date)
                            ->addDays($item->billed_days)->format('Y-m-d');
                        $client->plan_recurring_date = $next_recurring_date;
                        $client->plan_status = 'Active';
                        $client->save();
                    } else {
                        $next_recurring_date = Carbon::now()
                            ->addDays($item->billed_days)->format('Y-m-d');
                        $client->plan_recurring_date = $next_recurring_date;
                        $client->plan_status = 'Active';
                        $client->save();
                    }
                }

                //update team link to active
                if ($item->team_subscription_id) {

                    $team = TeamSubscription::find($item->team_subscription_id);
                    if ($team) {
                        //increment team members 
                        if ($item->team_members_increment_by) {
                            $team->increment('team_members', $item->team_members_increment_by);
                            $team->save();
                        }
                    } else {
                        //update  active  for team if the same  plan _reccuring_date 
                        if ($item->team_recurring_date) {
                            if (
                                $item->team_recurring_date == $client->plan_recurring_date && $item->team_recurring_date >= $date
                            ) {
                                $team->team_recurring_date = $item->team_recurring_date;
                                $team->sub_status = 'Active';
                                $team->opted_out = 'No';
                                $team->opted_out_date = null;
                                $team->team_members = $item->team_members;
                                $team->save();
                            }
                        }
                        //extend team subscription if has billed days
                        elseif ($item->billed_days) {
                            //extend team susbcription is is active 
                            if ($team->team_recurring_date >= $date) {
                                $next_recurring_date = Carbon::parse($team->team_recurring_date)
                                    ->addDays($item->billed_days)->format('Y-m-d');
                                $team->team_recurring_date =   $next_recurring_date;
                                $team->sub_status = 'Active';
                                $team->opted_out = 'No';
                                $team->opted_out_date = null;
                                $team->team_members = $item->team_members;
                                $team->save();
                            } else {
                                //extend team subscription is is Inactive 
                                $next_recurring_date = Carbon::now()
                                    ->addDays($item->billed_days)->format('Y-m-d');
                                $team->team_recurring_date =   $next_recurring_date;
                                $team->sub_status = 'Active';
                                $team->opted_out = 'No';
                                $team->opted_out_date = null;
                                $team->team_members = $item->team_members;
                                $team->save();
                            }
                        }
                    }
                }
            }
        }

        if ($invoice->sms_limit > 0) {
            SMSTransaction::create([
                'cl_id' => $invoice->cl_id,
                'amount' => $invoice->sms_limit
            ]);
        }
        $invoice->status = 'Paid';
        $invoice->datepaid = date('Y-m-d');
        $invoice->total_paid = $invoice->total;
        $invoice->save();
        return;
    }



    //generate invoice
    function generateInvoice($client = null, $invoice_type = 'Single', $items = [], $invoices = null, $message = null, $duedate = null, $sms_quantity = null)
    {


        $invoice_no = $this->generateInvoiceNo();
        $inserts = [];
        $subtotal = 0;
        $trans_amount = 0;
        $tax = 0;
        $discount = 0;
        $total = 0;
        $sms_limit = 0;
        //mass invoices
        if ($invoice_type == 'Mass') {
            $invoices->update(['mass_invoice_no' => $invoice_no]);
        } else {
            $cal = new PriceCalculation();
            foreach ($items as $item) {
                $calc = $cal->invoicePriceCalculation(
                    $client,
                    $item
                );
                $subtotal += $calc['amount'];
                $discount       +=  $calc['discount'];
                $total           += $calc['total'];
                $trans_amount += $calc['trans_amount'];
                $tax += $calc['tax'];
                $sms_limit += $sms_quantity ?? $item['plan']->sms_limit ?? $item['plan']->units ?? 0;
                $inserts[] = [
                    'invoice_no' => $invoice_no,
                    'description' => $item["description"] ?? $item['plan']->name ??  $item['plan']->bundle_name ?? $item["description"] ?? "Purchase",
                    'price' =>   $calc['price'],
                    'amount' => $calc['amount'],
                    'plan_id' => $item['plan_id'] ?? null,
                    'pro_subscription_id' => $item['pro_subscription_id'] ?? null,
                    'pro_recurring_date' => $item['pro_recurring_date'] ?? null,
                    'quantity' => $item['quantity'],
                    "model" => class_basename($item['plan']),
                    "model_id" => $item['plan']->id,
                    'change_plan_now' => $item['change_plan_now'] ?? 0,
                    'billed_days' => $item['billed_days'] ?? 30,
                    'billed_frequency' => $item['billed_frequency'] ?? 1,
                    'quantity' => $item['quantity'] ?? null,
                    'team_subscription_id' => $item['team_subscription_id'] ?? null,
                    'team_recurring_date' => $item['team_recurring_date'] ?? null,
                    'team_members' => $item['team_members'] ?? null,
                    'team_members_increment_by' => $item['team_members_increment_by'] ?? null,
                ];
            }

            $inv               = new Invoices();
            $inv->cl_id        = $client->id;
            $inv->invoice_no   = $invoice_no;
            $inv->plan_id      = $client->plan_id;
            $inv->created      = date('Y-m-d');
            $inv->duedate      = $duedate ?? date('Y-m-d', strtotime('+7 Day', strtotime("today")));
            $inv->subtotal     = $subtotal;
            $inv->tax            = $tax;
            $inv->discount       =  $discount;
            $inv->total            = $total;
            $inv->status       = 'Unpaid';
            $inv->trans_amount      = $trans_amount;
            $inv->sms_limit       = $sms_limit;
            $inv->save();
            $inv = Invoices::find($inv->id);
            InvoiceItems::upsert($inserts, ['id']);
        }
        $items = InvoiceItems::whereHas('invoice', function ($q) use ($invoice_no) {
            $q->where('mass_invoice_no', $invoice_no)
                ->orWhere('invoice_no', $invoice_no);
        })->get();
        $message   = $message ?? 'Please find attached invoice. You can pay this invoice using business number ' . env('MPESA_BUSINESS_SHORT_CODE') . ' and account number ' . $invoice_no;
        $subject     = 'Pay for invoice';

        // $file_name       = 'invoice-' . $invoice_no . '.pdf';
        // $pdf = PDF::loadView('payments.pdf-invoice', compact('client', 'inv', 'items'));
        // $file_path = $pdf->output();
        // $attachment_path = '';
        // Mail::to($client->email)->send(new SendInvoice($client->fname . ' ' . $client->lname, $subject, $message, $inv, 'Single', $attachment_path, $file_path, $file_name));
        return  $invoice_no;
    }

    public  function generateInvoiceNo()
    {
        $invoice_no = strtoupper(substr(bin2hex(random_bytes(10)), 0, 10));
        $inv = Invoices::where('invoice_no', $invoice_no)
            ->orWhere('mass_invoice_no', $invoice_no);
        if ($inv->count() > 0) {
            $this->generateInvoiceNo();
        } else {
            return $invoice_no;
        }
    }


    public function generateReceiptNo()
    {
        $receipt_no = strtoupper(substr(bin2hex(random_bytes(10)), 0, 10));
        $rec = Receipts::where('receipt_no', $receipt_no);
        if ($rec->count() > 0) {
            $this->generateReceiptNo();
        } else {
            return  $receipt_no;
        }
    }
    public function generateReceipt($invoice)
    {
        $receipt_no = $this->generateReceiptNo();
        $receipt = new Receipts();
        $client = Client::find($invoice->cl_id);

        $receipt->receipt_no = $receipt_no;
        $receipt->invoice_no = $invoice->invoice_no;
        $receipt->sms_limit = $invoice->sms_limit;
        $receipt->cl_id = $invoice->cl_id;
        $receipt->plan_id = $client->plan_id;
        $receipt->datepaid = date('Y-m-d');
        $receipt->subtotal = $invoice->subtotal;
        $receipt->trans_amount = $invoice->trans_amount;
        $receipt->discount = $invoice->discount;
        $receipt->tax = $invoice->tax;
        $receipt->total = $invoice->total;
        $receipt->pmethod = 'MPESA';
        $receipt->save();

        $items = InvoiceItems::whereHas('invoice', function ($q) use ($receipt) {
            $q->where('invoice_no', $receipt->invoice_no)
                ->orWhere('mass_invoice_no', $receipt->invoice_no);
        })->get();

        $pdf = PDF::loadView('payments.pdf-receipt', compact('receipt', 'items'));
        $file_name       = 'receipt-' . $receipt_no . '.pdf';
        $file_path = $pdf->output();
        $attachment_path = $file_path;
        $message   = 'Please find attached receipt';
        $name = $client->fname . ' ' . $client->lname;
        $subject     = 'Your receipt';

        // Mail::to($client->email)->send(new SendReceipt($name, $subject, $message, $attachment_path, $file_path, $file_name));

        return  $receipt_no;
    }

    //check if payment is paid
    public function checkInvoicePayment(Request $request, $invoice_no)
    {
        $errors = [];
        $to_pay = 0;
        $invoices = Invoices::where('mass_invoice_no', $invoice_no)->orWhere('invoice_no', $invoice_no);
        if ($invoices->count() == 0) {
            $errors[] = 'The invoice was not found';
        } else {
            $to_pay = $invoices->sum('total') - $invoices->sum('total_paid');

            if ($invoices->where('status', '!=', 'Paid')->count() > 0) {
                $errors[] = 'The payment has not yet been finished, or the invoice has not yet been paid in full.';
            }
        }
        if ($to_pay > 150000) {
            $to_pay = 150000;
        }
        if (count($errors) > 0) {

            return [
                'errors' => $errors,
                'to_pay' => $to_pay
            ];
        }

        return ["The payment was successfully completed."];
    }
}
