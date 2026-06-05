<?php

namespace App\Http\Controllers\Team\Pro;

use App\Http\Controllers\Controller;
use App\Models\ShortcodeTransaction;
use Illuminate\Http\Request;
use App\DataTables\ShortcodeTransactionDataTable;
use App\DataTables\ProSubscriptionsDataTable;
use App\Models\ProSubscription;
use Illuminate\Support\Carbon;
use App\Models\Staff;
use App\Helpers\SmsHelper;
use App\Client;
use App\ContactList;
use App\ImportPhoneNumber;
use libphonenumber\PhoneNumberUtil;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ShortcodeTransactionDataTable $dataTable)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        if ($request->start_date) {
            $start_date = Carbon::parse($request->start_date)->startOfDay()->format('Y-m-d H:i:s');
        }

        if ($request->end_date) {
            $end_date = Carbon::parse($request->end_date)->endOfDay()->format('Y-m-d H:i:s');
        }

        $startDay = Carbon::now()->startOfDay()->format('Y-m-d H:i:s');
        $endDay = Carbon::now()->endOfDay()->format('Y-m-d H:i:s');

        $start_month = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
        $end_month = Carbon::now()->endOfMonth()->format('Y-m-d H:i:s');

        $todaySalesGroup = ShortcodeTransaction::whereBetween('transaction_date', [$startDay, $endDay])
            ->whereHas('proSubscription', function ($q) {
                $q->where('sub_status', 'Active')
                    ->where('cl_id', auth('team')->user()->cl_id);
            })->selectRaw('sum(amount) as amount, payment_method')->groupBy('payment_method')->get();

        $monthSalesGroup = ShortcodeTransaction::whereBetween('transaction_date', [$start_month, $end_month])
            ->whereHas('proSubscription', function ($q) {
                $q->where('sub_status', 'Active')
                    ->where('cl_id', auth('team')->user()->cl_id);
            })->selectRaw('sum(amount) as amount, payment_method')->groupBy('payment_method')->get();

        $totalSalesGroup = ShortcodeTransaction::whereHas('proSubscription', function ($q) {
            $q->where('sub_status', 'Active')
                ->where('cl_id', auth('team')->user()->cl_id);
        })->selectRaw('sum(amount) as amount, payment_method')->groupBy('payment_method')->get();


        return $dataTable->with(
            [
                'cl_id' => auth('team')->user()->cl_id,
                'start_date' => $start_date,
                'end_date' => $end_date,

            ]
        )
            ->render(
                'client.pro.transaction.index',
                compact('todaySalesGroup', 'totalSalesGroup', 'monthSalesGroup')
            );
    }
    public function businesses(ProSubscriptionsDataTable $dataTable)
    {


        return $dataTable->with(
            [
                'cl_id' => auth('team')->user()->cl_id,
                'transactions_per_business' => true,

            ]
        )->render('client.pro.per-business');
    }

    public function transactionsPerBusiness($id, Request $request, ShortcodeTransactionDataTable $dataTable)
    {

        $sub = ProSubscription::where('id', $id)->where('cl_id', auth('team')->user()->cl_id)->first();

        if (!$sub) {
            return back()->withErrors('The subscription not found');
        }

        if (!$sub->shortcode) {
            return back()->withErrors("You don't have shortcode to view transactions");
        }

        if ($sub->shortcode_status == 'Incomplete') {
            return redirect('/integration/incomplete');
        }

        if ($sub->sub_status == 'Inactive') {
            return back()->withErrors("Your subscription is not active");
        }


        $start_date = $request->start_date;
        $end_date = $request->end_date;

        if ($request->start_date) {
            $start_date = Carbon::parse($request->start_date)->startOfDay()->format('Y-m-d H:i:s');
        }
        if ($request->end_date) {
            $end_date = Carbon::parse($request->end_date)->endOfDay()->format('Y-m-d H:i:s');
        }
        $startDay = Carbon::now()->startOfDay()->format('Y-m-d H:i:s');
        $endDay = Carbon::now()->endOfDay()->format('Y-m-d H:i:s');

        $start_month = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
        $end_month = Carbon::now()->endOfMonth()->format('Y-m-d H:i:s');

        $todaySalesGroup = ShortcodeTransaction::whereBetween('transaction_date', [$startDay, $endDay])
            ->whereHas('proSubscription', function ($q) use ($id) {
                $q->where('id', $id)
                    ->where('sub_status', 'Active')
                    ->where('cl_id', auth('team')->user()->cl_id);
            })->selectRaw('sum(amount) as amount, payment_method')->groupBy('payment_method')->get();

        $monthSalesGroup = ShortcodeTransaction::whereBetween('transaction_date', [$start_month, $end_month])
            ->whereHas('proSubscription', function ($q) use ($id) {
                $q->where('id', $id)
                    ->where('sub_status', 'Active')
                    ->where('cl_id', auth('team')->user()->cl_id);
            })->selectRaw('sum(amount) as amount, payment_method')->groupBy('payment_method')->get();

        $totalSalesGroup = ShortcodeTransaction::whereHas('proSubscription', function ($q) use ($id) {
            $q->where('id', $id)
                ->where('sub_status', 'Active')
                ->where('cl_id', auth('team')->user()->cl_id);
        })->selectRaw('sum(amount) as amount, payment_method')->groupBy('payment_method')->get();

        return $dataTable->with(
            [
                'pro_subscription_id' => $id,
                'cl_id' => auth('team')->user()->cl_id,
                'start_date' => $start_date,
                'end_date' => $end_date,
            ]
        )
            ->render(
                'client.pro.transaction.index',
                compact('todaySalesGroup', 'totalSalesGroup', 'monthSalesGroup')
            );
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $subs = ProSubscription::where('sub_status', 'Active')
            ->where('cl_id', auth('team')->user()->cl_id)->get();

        return view('client.pro.transaction.create', compact('subs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $payment_methods = 'Cash,Cheque,Debit Card,Credit Card,Mobile Payment,Electronic Bank Transfer,Other';

        $request->validate([
            'amount' => 'required|numeric',
            'subscription' => 'required',
            'payment_method' => "required|in:$payment_methods",
        ]);
        $sub = ProSubscription::where('id', $request->subscription)->where('sub_status', 'Active')
            ->where('cl_id', auth('team')->user()->cl_id)->first();
        if (!$sub) {
            return back()->withErrors('Subcription not found or not active');
        }

        $client = Client::find(auth('team')->user()->cl_id);

        $transaction = new ShortcodeTransaction();
        $transaction->trans_id = $request->transaction_id;
        $transaction->pro_subscription_id = $request->subscription;
        $transaction->amount = $request->amount;
        $transaction->payment_method = $request->payment_method;
        $transaction->payment_details = $request->payment_details;
        $transaction->transaction_date = $request->transaction_date ?? date('Y-m-d H:i:s');
        $transaction->phone_number = $request->phone_number;
        $transaction->first_name = $request->first_name;
        $transaction->last_name = $request->last_name;
        $transaction->save();


        $PhoneNumber = $request->phone_number;
        $PhoneNumber = (substr($PhoneNumber, 0, 1) == "+") ? str_replace("+", "", $PhoneNumber) : $PhoneNumber;
        $PhoneNumber = (substr($PhoneNumber, 0, 1) == "0") ? preg_replace("/^0/", "254", $PhoneNumber) : $PhoneNumber;
        $PhoneNumber = strlen($PhoneNumber) == 9 ? "254{$PhoneNumber}" : $PhoneNumber;
        if (is_numeric($PhoneNumber)) {
            $phoneUtil = PhoneNumberUtil::getInstance();
            $phoneNumberObject = $phoneUtil->parse('+' . $PhoneNumber, null);
            $isValid = $phoneUtil->isValidNumber($phoneNumberObject);

            if ($isValid) {
                $check_phone_client = ImportPhoneNumber::where('group_name', $sub->business_name)
                    ->where('user_id', auth('team')->user()->cl_id)->first();

                $check_phone_admin = ImportPhoneNumber::where('group_name', $sub->business_name)
                    ->where('user_id', 0)->first();
                if ($check_phone_client) {
                    $exist = ContactList::where('phone_number', $PhoneNumber)->where('pid',  $check_phone_client->id)->first();
                    if (!$exist) {
                        $contact = new ContactList();
                        $contact->pid =  $check_phone_client->id;
                        $contact->phone_number = $PhoneNumber;
                        $contact->first_name = $request->first_name;
                        $contact->last_name = $request->last_name;
                        $contact->email_address = $request->email;
                        $contact->user_name = $request->username;
                        $contact->company = $request->company;
                        $contact->save();
                    }
                }
                if ($check_phone_admin) {
                    $exist = ContactList::where('phone_number', $PhoneNumber)->where('pid',  $check_phone_admin->id)->first();
                    if (!$exist) {
                        $contact = new ContactList();
                        $contact->pid = $check_phone_admin->id;
                        $contact->phone_number = $PhoneNumber;
                        $contact->first_name = $request->first_name;
                        $contact->last_name = $request->last_name;
                        $contact->email_address = $request->email;
                        $contact->user_name = $request->username;
                        $contact->company = $request->company;
                        $contact->save();
                    }
                }
            }
        }




        if ($request->phone_number) {
            $message = "Dear valued customer, Thank you for entrusting us with your recent purchase and service requirements. We appreciate your business and hope you are pleased with your purchase. We appreciate your support and hope to see you again. Best wishes, $sub->business_name. This message is sent via Scribble. https://scribble.ke/ A Citrus Labs Limited product.";
            $sms = new SmsHelper();
            $sms->sendProSms(prosub: $sub, client: $client, phone: $request->phone_number, message: $message, minutes: 10);
        }

        return redirect('/transactions')->with('message', 'Transaction added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ShortcodeTransaction  $shortcodeTransaction
     * @return \Illuminate\Http\Response
     */
    public function show(ShortcodeTransaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ShortcodeTransaction  $shortcodeTransaction
     * @return \Illuminate\Http\Response
     */
    public function edit(ShortcodeTransaction $transaction)
    {
        if ($transaction->payment_method == 'Mpesa') {
            return back()->withErrors("You can't edit mpesa payments");
        }
        $subs = ProSubscription::where('sub_status', 'Active')
            ->where('cl_id', auth('team')->user()->cl_id)->get();

        return view('client.pro.transaction.edit', compact('subs', 'transaction'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShortcodeTransaction  $shortcodeTransaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ShortcodeTransaction $transaction)
    {
        if ($transaction->payment_method == 'Mpesa') {
            return back()->withErrors("You can't edit mpesa payments");
        }
        $payment_methods = 'Cash,Cheque,Debit Card,Credit Card,Mobile Payment,Electronic Bank Transfer,Other';

        $request->validate([
            'amount' => 'required|numeric',
            'subscription' => 'required',
            'payment_method' => "required|in:$payment_methods",
        ]);
        $sub = ProSubscription::where('id', $request->subscription)->where('sub_status', 'Active')
            ->where('cl_id', auth('team')->user()->cl_id)->first();
        if (!$sub) {
            return back()->withErrors('Subcription not found or not active');
        }

        $transaction->trans_id = $request->transaction_id;
        $transaction->pro_subscription_id = $request->subscription;
        $transaction->amount = $request->amount;
        $transaction->payment_method = $request->payment_method;
        $transaction->payment_details = $request->payment_details;
        $transaction->transaction_date = $request->transaction_date ?? date('Y-m-d H:i:s');
        $transaction->phone_number = $request->phone_number;
        $transaction->first_name = $request->first_name;
        $transaction->last_name = $request->last_name;
        $transaction->save();

        return back()->with('message', 'Transaction updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShortcodeTransaction  $shortcodeTransaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShortcodeTransaction $transaction)
    {
        //
    }

    public function workHistory(ShortcodeTransactionDataTable $dataTable, Request $request, $work_staff_id)
    {
        $staff = Staff::where('id', $work_staff_id)
            ->where('cl_id', auth('team')->user()->cl_id)
            ->first();
        if (!$staff) {
            return back()->withErrors('Staff Not found');
        }
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $startDay = Carbon::now()->startOfDay()->format('Y-m-d H:i:s');
        $endDay = Carbon::now()->endOfDay()->format('Y-m-d H:i:s');

        $start_month = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
        $end_month = Carbon::now()->endOfMonth()->format('Y-m-d H:i:s');
        if ($request->start_date) {
            $start_date = Carbon::parse($request->start_date)->startOfDay()->format('Y-m-d H:i:s');
        }

        if ($request->end_date) {
            $end_date = Carbon::parse($request->end_date)->endOfDay()->format('Y-m-d H:i:s');
        }

        $todaySalesGroup = ShortcodeTransaction::where('bill_ref_number', $staff->unique_id)
            ->whereBetween('transaction_date', [$startDay, $endDay])
            ->whereHas('proSubscription', function ($q) {
                $q->where('sub_status', 'Active')
                    ->where('cl_id', auth('team')->user()->cl_id);
            })->selectRaw('sum(amount) as amount, payment_method')->groupBy('payment_method')->get();

        $monthSalesGroup = ShortcodeTransaction::where('bill_ref_number', $staff->unique_id)
            ->whereBetween('transaction_date', [$start_month, $end_month])
            ->whereHas('proSubscription', function ($q) {
                $q->where('sub_status', 'Active')
                    ->where('cl_id', auth('team')->user()->cl_id);
            })->selectRaw('sum(amount) as amount, payment_method')->groupBy('payment_method')->get();

        $totalSalesGroup = ShortcodeTransaction::where('bill_ref_number', $staff->unique_id)
            ->whereHas('proSubscription', function ($q) {
                $q->where('sub_status', 'Active')
                    ->where('cl_id', auth('team')->user()->cl_id);
            })->selectRaw('sum(amount) as amount, payment_method')->groupBy('payment_method')->get();

        return $dataTable->with(
            [
                'cl_id' => auth('team')->user()->cl_id,
                'work_staff_id' => $staff->unique_id,
                'start_date' => $start_date,
                'end_date' => $end_date,
            ]
        )->render('client.pro.transaction.index', compact('staff', 'todaySalesGroup', 'totalSalesGroup', 'monthSalesGroup'));
    }
}
