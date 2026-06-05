<?php

namespace App\Http\Controllers;

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
use Log;
use Carbon\Carbon;
use PDF;

class PaymentInvoiceController extends Controller
{

    // confirm mpesa single payment
    public function singleInvoicePayment(Request $request, $invoice_no)
    {

        $invoice = Invoices::where('invoice_no', $invoice_no)->first();
        $errors = [];
        if (!$invoice) {
            $errors[] = 'The invoice number was not found.';
        }
        if (Invoices::where([['status', '=', 'Paid'], ['invoice_no', "=", $invoice_no]])->count() > 0) {
            $errors[] = 'The invoice has already been paid';
        }
        $date_now = date("Y-m-d");
        if ($date_now > $invoice->duedate) {
            $errors[] = 'The invoice has already Expired.';
        }

        if (!$request->phone_number) {
            $errors[] = 'You must enter valid phone';
        }
        if (count($errors) > 0) {
            return [
                'errors' => $errors
            ];
        }

        $payMpesa = new MpesaController();
        $stkPush = $payMpesa->stkSimulation($invoice_no, $invoice->cl_id, $invoice->total, $request->phone_number, 'Payment for Sms', 'Single');

        if (is_array($stkPush)) {
            return [
                'errors' => $stkPush
            ];
        }

        return ["Please check the notification on " . $request->phone_number . " enter the pin to complete the payment. If it fails please use Mpesa Paybill method."];
    }


    // mass payment
    public function massInvoicePayment(Request $request, $mass_invoice_no)
    {

        $mass_invoice = MassInvoices::where('mass_invoice_no', $mass_invoice_no)->first();
        $invoices_check = Invoices::where([
            ['mass_invoice_no', '=', $mass_invoice->mass_invoice_no],
            ['status', '=', 'Paid']
        ]);
        $date_now = date("Y-m-d");

        $errors = [];
        if (!$mass_invoice) {
            $errors[] = 'The record was not found ';
        }

        if ($invoices_check->count() > 0) {
            $errors[] = 'One of the invoices as been paid. You have go to all invoices page';
        }
        if ($date_now > $mass_invoice->duedate) {
            $errors[] = 'The invoice has already Expired.';
        }

        if (!$request->phone_number) {
            $errors[] = 'You must enter valid phone';
        }
        if (count($errors) > 0) {
            return [
                'errors' => $errors
            ];
        }

        $payMpesa = new MpesaController();
        $stkPush = $payMpesa->stkSimulation($mass_invoice_no, $mass_invoice->cl_id, $mass_invoice->total, $request->phone_number, 'Payment for Sms', 'Mass');

        if (is_array($stkPush)) {
            return [
                'errors' => $stkPush
            ];
        }

        return ["Please check the notification on " . $request->phone_number . " enter the pin to complete the payment"];
    }

    //update after payment
    public function updateAfterPayment($invoice_no, $type)
    {
        $invoice = null;
        $items = [];
        if ($type == "Mass") {
            $invoice = MassInvoices::where('mass_invoice_no', $invoice_no)->first();
            $invoice->status = 'Paid';
            $invoice->datepaid = date('Y-m-d');
            $invoice->save();

            //paid for invoices table
            Invoices::where('mass_invoice_no', $invoice_no)->update(
                ['status' => 'Paid', 'datepaid' => date('Y-m-d')]
            );

            //select all inoices related;
            $inv_nos = Invoices::where('mass_invoice_no', $invoice_no)->get();
            $c = [];
            foreach ($inv_nos as $k) {
                $c[] = $k->invoice_no;
            }
            $items = InvoiceItems::whereIn('invoice_no', $c)->get();
        } else {
            $invoice = Invoices::where('invoice_no', $invoice_no)->first();
            $invoice->status = 'Paid';
            $invoice->datepaid = date('Y-m-d');
            $invoice->save();
            $items = invoiceItems::where('invoice_no', $invoice_no)->get();
        }
        foreach ($items as $item) {
            $client = Client::find($invoice->cl_id);
            if ($item->plan_id) {
                $client->plan_recurring_date = date('Y-m-d', strtotime('+30 days', strtotime("Today")));
                $client->plan_status = 'Active';
                $client->plan_id = $item->plan_id;
                $client->save();
            }
            if ($item->pro_subscription_id) {
                $pro = ProSubscription::find($item->pro_subscription_id);
                $pro->sub_status = 'Active';
                $pro->opted_out = 'No';
                $pro->opted_out_date = null;
                $pro->plan_recurring_date = $item->plan_recurring_date ?? date('Y-m-d', strtotime('+30 days', strtotime("Today")));
                $pro->save();
            }
        }
        $client = Client::find($invoice->cl_id);
        $client->increment('sms_limit', $invoice->sms_limit);
        $client->save();
        SMSTransaction::create([
            'cl_id' => $invoice->cl_id,
            'amount' => $invoice->sms_limit
        ]);
        return true;
    }



    //generate invoice

    function generateInvoice($client, $invoice_type, $items = [], $invoices = null, $message = null)
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
            $sms_limit = $invoices->sum('sms_limit');
            $tax = $invoices->sum('tax');
            $discount = $invoices->sum('discount');
            $total = $invoices->sum('total');
            $subtotal = $invoices->sum('subtotal');
            $trans_amount = $invoices->sum('trans_amount');
            $inv_related = [];

            foreach ($invoices as  $v) {
                $inv_related[] = $v->invoice_no;
            }

            $inv = new MassInvoices();
            $inv->mass_invoice_no = $invoice_no;
            $inv->sms_limit = $sms_limit;
            $inv->cl_id = $client->id;
            $inv->subtotal = $subtotal;
            $inv->discount = $discount;
            $inv->tax = $tax;
            $inv->trans_amount = $trans_amount;
            $inv->total = $total;
            $inv->plan_id = $client->plan_id;
            $inv->duedate = date('Y-m-d');
            $inv->created = date('Y-m-d');
            $inv->save();
            $invoices->update(['mass_invoice_no' => $invoice_no]);
            $items = InvoiceItems::whereIn('invoice_no', $inv_related)->get();
            $inv = MassInvoices::where('mass_invoice_no', $invoice_no)->first();
        } else {

            $cal = new PriceCalculation();
            foreach ($items as $item) {
                $calc = $cal->invoicePriceCalculation(
                    $client,
                    $item
                );
                $subtotal += $calc['amount'];
                $discount       +=  $calc['discount'];
                $total           += $calc['price'];
                $trans_amount += $calc['trans_amount'];
                $tax += $calc['tax'];
                $sms_limit += $item['plan']->sms_limit ?? $item['plan']->units;
                $inserts[] = [
                    'invoice_no' => $invoice_no,
                    'description' => $item['plan']->name ??  $item['plan']->bundle_name ?? $item["description"] ?? "Purchase",
                    'price' =>   $item['plan']->price,
                    'amount' => $calc['amount'],
                    'plan_id' => $item['plan_id'] ?? null,
                    'pro_subscription_id' => $item['pro_subscription_id'] ?? null,
                    'plan_recurring_date' => $item['plan_recurring_date'] ?? null,
                    'quantity' => $item['quantity'],
                    "model" => class_basename($item['plan']),
                    "model_id" => $item['plan']->id,

                ];
            }

            $inv               = new Invoices();
            $inv->cl_id        = $client->id;
            $inv->invoice_no   = $invoice_no;
            $inv->plan_id      = $client->plan_id;
            $inv->created      = date('Y-m-d');
            $inv->duedate      = date('Y-m-d', strtotime('+1 Day', strtotime("today")));
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

            $items = InvoiceItems::where('invoice_no', $invoice_no)->get();
        }

        $message   = $message ?? 'Please find attached invoice. You can pay this invoice using business number ' . env('MPESA_BUSINESS_SHORT_CODE') . ' and account number ' . $invoice_no;
        $subject     = 'Pay for invoice';

        $file_name       = 'invoice-' . $invoice_no . '.pdf';
        $pdf = PDF::loadView('payments.pdf-invoice', compact('client', 'inv', 'items'));
        $file_path = $pdf->output();
        $attachment_path = '';
        Mail::to($client->email)->send(new SendInvoice($client->fname . ' ' . $client->lname, $subject, $message, $inv, 'Single', $attachment_path, $file_path, $file_name));
        return  $invoice_no;
    }

    public  function generateInvoiceNo()
    {
        $invoice_no = strtoupper(substr(bin2hex(random_bytes(10)), 0, 10));
        $mass = MassInvoices::where('mass_invoice_no', $invoice_no);
        $inv = Invoices::where('invoice_no', $invoice_no);
        if ($inv->count() > 0 || $mass->count() > 0) {
            $this->generateInvoiceNo();
        } else {
            return $invoice_no;
        }
    }

    public function generateInvoiceRemaining(
        $client,
        $plan,
        array $items,
        $expiryDate,
        $days = 30,
        $message = null

    ) {
        $today = Carbon::now()->startOfDay();
        $days_remaining = ceil($expiryDate->diffInHours($today) / 24);

        if ($days_remaining > $days) {
            $days_remaining = $days;
        }
        $remDaysFranction =  $days_remaining / $days;

        $invoice_no = $this->generateInvoiceNo();
        $inserts = [];
        $subtotal = 0;
        $trans_amount = 0;
        $tax = 0;
        $discount = 0;
        $total = 0;
        $sms_limit = 0;

        foreach ($items as $item) {
            $cal = new PriceCalculation();
            $calc = $cal->invoicePriceCalculation(
                $client,
                $item

            );


            $subtotal += $calc['amount'] * $remDaysFranction;
            $discount       +=  $calc['discount'] * $remDaysFranction;
            $total           += $calc['price'] * $remDaysFranction;
            $trans_amount += $calc['trans_amount'] * $remDaysFranction;
            $tax += $calc['tax'] * $remDaysFranction;
            $price =   $plan->price * $remDaysFranction;
            $amount = $calc['amount'] * $remDaysFranction;
            $inserts[] = [
                'invoice_no' => $invoice_no,
                'description' => $item['plan']->name ??  $item['plan']->bundle_name ?? $item["description"] ?? "Purchase",
                'price' =>  $price,
                'amount' => $amount,
                'plan_id' => $item['plan_id'] ?? null,
                'pro_subscription_id' => $item['pro_subscription_id'] ?? null,
                'plan_recurring_date' => $item['plan_recurring_date'] ?? null,
                'quantity' => $item['quantity'] ?? 1,
                "model" => class_basename($item['plan']),
                "model_id" => $item['plan']->id,
            ];
        }

        $inv               = new Invoices();
        $inv->cl_id        = $client->id;
        $inv->invoice_no   = $invoice_no;
        $inv->plan_id      = $client->plan_id;
        $inv->created      = date('Y-m-d');
        $inv->duedate      = date('Y-m-d');
        $inv->subtotal     = $subtotal;
        $inv->tax            = $tax;
        $inv->discount       =  $discount;
        $inv->total            = ceil($total);
        $inv->status       = 'Unpaid';
        $inv->trans_amount      = $trans_amount;
        $inv->sms_limit       = $sms_limit;
        $inv->save();
        $inv = Invoices::find($inv->id);

        InvoiceItems::upsert($inserts, ['id']);

        $items = InvoiceItems::where('invoice_no', $invoice_no)->get();
        $inv = Invoices::find($inv->id);
        $items = Invoices::where('invoice_no', $invoice_no)->get();
        $client = Client::find($client->id);
        $message   = $message ?? 'Please find attached invoice. You can pay this invoice using business number ' . env('MPESA_BUSINESS_SHORT_CODE') . ' and account number '
            . $invoice_no;
        $subject     = 'Payment for Scribble Pro';

        $file_name       = 'invoice-' . $invoice_no . '.pdf';
        $pdf = PDF::loadView('payments.pdf-invoice', compact('client', 'inv', 'items'));
        $file_path = $pdf->output();
        $attachment_path = '';
        Mail::to($client->email)->send(new SendInvoice($client->fname . ' ' . $client->lname, $subject, $message, $inv, 'Single', $attachment_path, $file_path, $file_name));
        return  $invoice_no;
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
    public function generateReceipt($invoice_no, $type, $trans_id)
    {



        $receipt_no = $this->generateReceiptNo();
        $receipt = new Receipts();

        $invoice = null;
        if ($type == "Mass") {
            $invoice = MassInvoices::where('mass_invoice_no', $invoice_no)->first();
        } else {
            $invoice = Invoices::where('invoice_no', $invoice_no)->first();
        }
        $client = Client::find($invoice->cl_id);
        $items = InvoiceItems::whereHas('invoice', function ($q) use ($receipt) {
            $q->where('invoice_no', $receipt->invoice_no)
                ->orWhereHas('massInvoices', function ($q) use ($receipt) {
                    $q->where('mass_invoice_no', $receipt->invoice_no);
                });
        })->get();

        $receipt->receipt_no = $receipt_no;
        $receipt->invoice_no = $invoice_no;
        $receipt->sms_limit = $invoice->sms_limit;
        $receipt->mpesa_ref = $trans_id;
        $receipt->cl_id = $invoice->cl_id;
        $receipt->plan_id = $client->plan_id;
        $receipt->datepaid = date('Y-m-d');
        $receipt->subtotal = $invoice->subtotal;
        $receipt->trans_amount = $invoice->trans_amount;
        $receipt->discount = $invoice->discount;
        $receipt->tax = $invoice->tax;
        $receipt->total = $invoice->total;
        $receipt->type = $type;
        $receipt->pmethod = 'MPESA';
        $receipt->save();



        $items = InvoiceItems::whereHas('invoice', function ($q) use ($receipt) {
            $q->where('invoice_no', $receipt->invoice_no)
                ->orWhereHas('massInvoices', function ($q) use ($receipt) {
                    $q->where('mass_invoice_no', $receipt->invoice_no);
                });
        })->get();
        $pdf = PDF::loadView('payments.pdf-receipt', compact('receipt', 'items'));
        $file_name       = 'receipt-' . $receipt_no . '.pdf';
        $file_path = $pdf->output();
        $attachment_path = $file_path;
        $message   = 'Please find attached receipt';
        $name = $client->fname . ' ' . $client->lname;
        $subject     = 'Your receipt';
        Mail::to($client->email)->send(new SendReceipt($name, $subject, $message, $attachment_path, $file_path, $file_name));

        return  $receipt_no;
    }

    //check if payment is paid
    public function checkInvoicePayment(Request $request, $invoice_no)
    {
        $errors = [];
        $inv = null;
        if (!$request->invoice_type) {
            $errors[] = 'Bad Request';
        } else {

            if ($request->invoice_type == 'Mass') {
                $inv = MassInvoices::where('mass_invoice_no', $invoice_no)->first();
            } else {
                $inv = invoices::where('invoice_no', $invoice_no)->first();
            }

            if (!$inv) {
                $errors[] = 'The invoice was not found';
            } else if ($inv->status != 'Paid') {
                $errors[] = 'The payment has not yet been completed. Please wait.';
            }
        }

        if (count($errors) > 0) {

            return [
                'errors' => $errors
            ];
        }

        return ["The payment was successfully completed."];
    }
}
