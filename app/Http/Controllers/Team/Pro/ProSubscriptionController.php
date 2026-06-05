<?php

namespace App\Http\Controllers\Team\Pro;

use App\Client;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PaymentInvoiceController;
use App\Models\ProPlan;
use App\Models\ProSubscription;
use App\Plan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\General;
use Illuminate\Support\Facades\Validator;
use App\DataTables\ProSubscriptionsDataTable;
use App\Models\ProSubscriptionFile;
use App\SenderIdManage;
use Illuminate\Support\Facades\Storage;
use App\ImportPhoneNumber;
use App\SMSGateways;

class ProSubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProSubscriptionsDataTable $dataTable)
    {
        return $dataTable->with(
            [
                'cl_id' => auth('team')->user()->cl_id,
                'any_status' => true,
            ]
        )
            ->render('client.pro.sub.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $client = Client::where('id', auth('team')->user()->cl_id)
            ->where('plan_status', 'Active')->first();

        if (!$client) {
            return  back()->withErrors('Your main subscription is Inactive');
        }

        $proplan = ProPlan::where('status', 'active')
            ->where('plan_id', $client->plan_id)->first();
        if (!$proplan) {
            return back()->withErrors('There no pro plan for your main plan');
        }

        $all_sender_id = SenderIdManage::where('status', 'unblock')->get();
        $all_ids = [];
        foreach ($all_sender_id as $sid) {
            $client_array = json_decode($sid->cl_id);

            if (isset($client_array) && is_array($client_array) && in_array('0', $client_array)) {
                array_push($all_ids, $sid->id);
            } elseif (isset($client_array) && is_array($client_array) && in_array(auth('team')->user()->cl_id, $client_array)) {
                array_push($all_ids, $sid->id);
            }
        }
        $sender_ids = array_unique($all_ids);
        $sender_ids = SenderIdManage::whereIn('id', $sender_ids)->where('status', 'unblock')->get();

        if (count($sender_ids) == 0) {
            return back()->withErrors("You don't an active sender id");
        }

        $today = date('Y-m-d');
        if ($client->plan_recurring_date < $today) {
            return back()->withErrors('There is problem with your main subscription expiration. Please contact support.');
        }

        $expiryDate = Carbon::parse($client->plan_recurring_date)->endOfDay();
        $today = Carbon::now()->startOfDay();

        $days_remaining = ceil($expiryDate->diffInHours($today) / 24);

        if ($days_remaining < 2) {
            return back()->withErrors("You can't add pro subscription since your main subscription has expired or is expiring today. 
            You need to renew your main subscription. If you face difficult, Please contact support");
        }

        $gateways = json_decode(auth('team')->user()->client->sms_gateway);
        if (count($gateways) == 0) {
            return back()->withErrors("You have not been assigned sms gateway. Please contact support");
        }
        $sms_gateways = SMSGateways::whereIn('id', $gateways)->where('status', 'Active')->get();
        return view('client.pro.sub.create', compact('proplan', 'days_remaining', 'sender_ids', 'sms_gateways'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'business_name' => 'required',
            'services' => 'required',
            'phone_number' => 'required',
            'upload_files.*' => 'mimes:ppt,pptx,doc,docx,pdf,xls,xlsx,cvs,jpg,png,jpeg|max:50000',
            'sms_gateway' => 'required',
            'sender_id' => 'required'
        ]);



        if ($request->shortcode) {
            $pro = ProSubscription::where('shortcode', $request->shortcode)->first();
            if ($pro) {
                return back()->withInput()->withErrors('The shortcode is already been used for another business. Please contact support if you think it is error.');
            }
        }

        $client = Client::where('id', auth('team')->user()->cl_id)
            ->where('plan_status', 'Active')->first();

        if (!$client) {
            return back()->withErrors("You don't have an active main subscription plan.");
        }

        $proplan = ProPlan::where('status', 'active')
            ->where('plan_id', $client->plan_id)->first();

        if (!$proplan) {
            return back()->withErrors('The plan is either Inactive or does belong to your main subscription plan');
        }

        $expiryDate = Carbon::parse($client->plan_recurring_date)->endOfDay();
        $today = Carbon::now()->startOfDay();
        $days_remaining = ceil($expiryDate->diffInHours($today) / 24);

        $files = $request->upload_files;
        $disk = Storage::disk('private');

        $all_sender_id = SenderIdManage::where('status', 'unblock')->get();
        $all_ids = [];
        foreach ($all_sender_id as $sid) {
            $client_array = json_decode($sid->cl_id);

            if (isset($client_array) && is_array($client_array) && in_array('0', $client_array)) {
                array_push($all_ids, $sid->id);
            } elseif (isset($client_array) && is_array($client_array) && in_array(auth('team')->user()->cl_id, $client_array)) {
                array_push($all_ids, $sid->id);
            }
        }
        $sender_ids = array_unique($all_ids);
        $sender = SenderIdManage::whereIn('id', $sender_ids)->where('status', 'unblock')
            ->where('id', $request->sender_id)->first();

        if (!$sender) {
            return back()->withErrors("You don't an active sender id");
        }

        $today = date('Y-m-d');
        if ($client->plan_recurring_date < $today) {
            return back()->withErrors('There is problem with your main subscription expiration. Please contact support.');
        }

        $expiryDate = Carbon::parse($client->plan_recurring_date)->endOfDay();
        $today = Carbon::now()->startOfDay();

        $days_remaining = ceil($expiryDate->diffInHours($today) / 24);

        if ($days_remaining < 2) {
            return back()->withErrors("You can't add pro subscription since your main subscription has expired or is expiring today. 
            You need to renew your main subscription. If you face difficult, Please contact support");
        }
        $gateways = json_decode(auth('team')->user()->client->sms_gateway);
        if (!in_array($request->sms_gateway, $gateways)) {
            return back()->withErrors('Wrong sms gateway');
        }

        $sub = new ProSubscription();
        $sub->cl_id = auth('team')->user()->cl_id;

        $sub->shortcode = $request->shortcode;
        $sub->shortcode_type = $request->shortcode_type;
        $sub->sender_id = $sender->id;
        if ($request->shortcode) {
            $sub->shortcode_status = 'Incomplete';
        }
        $sub->developer_integrate = $request->developer_integrate;
        $sub->shortcode_type = $request->shortcode_type;
        $sub->business_name = $request->business_name;
        $sub->phone_number = $request->phone_number;
        $sub->pro_plan_id = $proplan->id;
        $sub->pro_recurring_date = $client->plan_recurring_date;
        $sub->sms_gateway = $request->sms_gateway;
        if ($proplan->price == 0) {
            $sub->sub_status = 'Active';
        }
        $sub->summary_time = $request->summary_time ?? '23:59:00';
        foreach ($request->services as $r) {
            $sub->$r = 1;
        }
        $sub->save();

        $check_phone_client = ImportPhoneNumber::where('group_name', $sub->business_name)
            ->where('user_id', auth('team')->user()->cl_id)->first();
        if (!$check_phone_client) {
            $phonebook = new ImportPhoneNumber();
            $phonebook->user_id = auth('team')->user()->cl_id;
            $phonebook->group_name = $sub->business_name;
            $phonebook->save();
        }

        $check_phone_admin = ImportPhoneNumber::where('group_name', $sub->business_name)
            ->where('user_id', 0)->first();
        if (!$check_phone_admin) {
            $phonebook = new ImportPhoneNumber();
            $phonebook->user_id = 0;
            $phonebook->group_name = $sub->business_name;
            $phonebook->save();
        }



        if ($files) {
            $store_files = [];
            foreach ($files as $file) {
                $originalname = $file->getClientOriginalName();
                $filename = basename($disk->put('/pro-subscription', $file));
                $files_email[] = ['filename' => $filename, 'originalname' => $originalname];

                $store_files[] = [
                    'pro_subscription_id' => $sub->id,
                    'filename' => $filename,
                    'originalname' => $originalname,
                ];
            }

            ProSubscriptionFile::insert($store_files);
        }

        if ($proplan->price > 0) {
            $message = [
                "Thank you for applying for Scribble PRO, the most advanced Bulk SMS service ever. We have received your application and we're excited to work on it as quickly as possible.",
                "Rest assured that we will notify you via email and Scribble notification as soon as your application is ready. In the meantime, if you have any questions or concerns, please don't hesitate to contact us by raising a ticket on the Scribble platform or by sending an email to scribble.support@citrus.co.ke.",
                "We would like to remind you that we will contact you on the given phone number $request->phone_number if we need any additional information from you.",
                "Thank you again for choosing Scribble PRO."
            ];

            $duedate = Carbon::now()->addDays(5);
            if ($days_remaining < 5) {
                $duedate = $client->plan_recurring_date;
            }
            $pay = new PaymentInvoiceController();

            $items[] = [
                'plan' => $proplan,
                'quantity' => 1,
                'pro_remaining_days' => $days_remaining,
                'pro_subscription_id' => $sub->id,
                'pro_recurring_date' => $client->plan_recurring_date,
            ];

            $invoice_no = $pay->generateInvoice(client: $client, items: $items, duedate: $duedate);

            $url = "//admin-pro." . env('APP_DOMAIN') . "/prosubscriptions/$sub->id/edit";
            $anchor = 'View Pro Subscription';
            $subject = 'New Pro Subscription';
            $message = 'You have new pro subscription. You can counter check if user has paid and follow up for mpesa integration if required';

            Mail::to(env('SUBSCRIPTIONS_EMAIL'))->send(new General($subject, 'Admin', $message, $url, $anchor));

            $name = auth('team')->user()->client->fname . ' ' . auth('team')->user()->client->lname;

            $subject = "Scribble PRO Application Received";
            Mail::to(auth('team')->user()->client->email)->send(new General($subject, $name, $message));
            session(['new-pro-sub' => true]);
            return redirect('user/invoices/view/' . $invoice_no)
                ->with('message', 'Please pay this invoice before due date.Please remember you need to have active main Scribble subscription for this invoice to be effective.');
        }
        return redirect('/')->with('message', 'You have successfully subscribed');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProSubscription  prosubscription
     * @return \Illuminate\Http\Response
     */
    public function show(ProSubscription $prosubscription)
    {
        return back()->withErrors('No allowed');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProSubscription  prosubscription
     * @return \Illuminate\Http\Response
     */
    public function edit(ProSubscription $prosubscription)
    {

        $client = Client::where('id', auth('team')->user()->cl_id)
            ->where('plan_status', 'Active')->first();

        if (!$client) {
            return back()->withErrors("You don't have an active main subscription plan.");
        }
        if ($prosubscription->cl_id != $client->id) {
            return back()->withErrors('wrong subscription');
        }
        $all_sender_id = SenderIdManage::where('status', 'unblock')->get();
        $all_ids = [];
        foreach ($all_sender_id as $sid) {
            $client_array = json_decode($sid->cl_id);

            if (isset($client_array) && is_array($client_array) && in_array('0', $client_array)) {
                array_push($all_ids, $sid->id);
            } elseif (isset($client_array) && is_array($client_array) && in_array(auth('team')->user()->cl_id, $client_array)) {
                array_push($all_ids, $sid->id);
            }
        }
        $sender_ids = array_unique($all_ids);
        $sender_ids = SenderIdManage::whereIn('id', $sender_ids)->where('status', 'unblock')->get();

        if (count($sender_ids) == 0) {
            return back()->withErrors("You don't an active sender id");
        }
        $gateways = json_decode(auth('team')->user()->client->sms_gateway);
        $sms_gateways = SMSGateways::whereIn('id', $gateways)->where('status', 'Active')->get();
        if (count($sms_gateways) == 0) {
            return back()->withErrors("You have not been assigned sms gateway. Please contact support");
        }
        return view('client.pro.sub.edit', compact('prosubscription',  'sender_ids', 'sms_gateways'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProSubscription  prosubscription
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProSubscription $prosubscription)
    {
        $this->validate($request, [
            'business_name' => 'required',
            'services' => 'required',
            'phone_number' => 'required',
            'sms_gateway' => 'required',
            'sender_id' => 'required',
            'otp' => 'required',
        ]);

        $client = Client::where('id', auth('team')->user()->cl_id)
            ->where('plan_status', 'Active')->first();

        if (!$client) {
            return back()->withErrors("You don't have an active main subscription plan.");
        }

        if ($prosubscription->cl_id != $client->id) {
            return back()->withErrors('wrong subscription');
        }

        $checkOtp = checkOtp(auth('team')->user()->client, $request->otp);

        if (!$checkOtp) {
            return back()->withInput()->withErrors('The OTP has eiter expired, used or does not exist');
        }

        $all_sender_id = SenderIdManage::where('status', 'unblock')->get();
        $all_ids = [];
        foreach ($all_sender_id as $sid) {
            $client_array = json_decode($sid->cl_id);

            if (isset($client_array) && is_array($client_array) && in_array('0', $client_array)) {
                array_push($all_ids, $sid->id);
            } elseif (isset($client_array) && is_array($client_array) && in_array(auth('team')->user()->cl_id, $client_array)) {
                array_push($all_ids, $sid->id);
            }
        }
        $sender_ids = array_unique($all_ids);
        $sender = SenderIdManage::whereIn('id', $sender_ids)->where('status', 'unblock')
            ->where('id', $request->sender_id)->first();

        if (!$sender == 0) {
            return back()->withErrors("You don't an active sender id");
        }


        $gateways = json_decode(auth('team')->user()->client->sms_gateway);

        if (!in_array($request->sms_gateway, $gateways)) {
            return back()->withErrors('Wrong sms gateway');
        }

        $prosubscription->shortcode_type = $request->shortcode_type;
        $prosubscription->sender_id = $sender->id;
        if ($request->shortcode) {
            $prosubscription->shortcode_status = 'Incomplete';
        }
        $prosubscription->developer_integrate = $request->developer_integrate;
        $prosubscription->shortcode_type = $request->shortcode_type;
        $prosubscription->business_name = $request->business_name;
        $prosubscription->phone_number = $request->phone_number;
        $prosubscription->sms_gateway = $request->sms_gateway;
        $prosubscription->summary_time = $request->summary_time ?? '23:59:00';
        $prosubscription->staff = 0;
        $prosubscription->transactions = 0;
        $prosubscription->visitors = 0;
        foreach ($request->services as $r) {
            $prosubscription->$r = 1;
        }
        $prosubscription->save();

        return back()->with('message', 'Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProSubscription  prosubscription
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProSubscription $prosubscription)
    {
        return back()->withErrors('No allowed');
    }

    public function optIn($id)
    {
        $sub = ProSubscription::where('cl_id', auth('team')->user()->cl_id)->where('id', $id)->first();
        if (!$sub) {
            return back()->withErrors('Subscription not found');
        }


        $client = Client::where('id', auth('team')->user()->cl_id)
            ->where('plan_status', 'Active')->first();

        if (!$client) {
            return back()->withErrors("You don't have an active main subscription plan.");
        }

        $proplan = ProPlan::where('status', 'active')
            ->where('plan_id', $client->plan_id)->first();

        if (!$proplan) {
            return back()->withErrors('The plan is either Inactive or does belong to your main subscription plan');
        }

        $expiryDate = Carbon::parse($client->plan_recurring_date)->endOfDay();
        $today = Carbon::now()->startOfDay();

        $days_remaining = ceil($expiryDate->diffInHours($today) / 24);

        if ($days_remaining < 2) {
            return back()->withErrors("You can't opt in since your main subscription has expired or is expiring today. 
            You need to renew your main subscription. If you face difficult, Please contact support");
        }
        if ($proplan->price > 0) {
            $pay = new PaymentInvoiceController();

            $items[] = [
                'plan' => $proplan,
                'quantity' => 1,
                'pro_remaining_days' => $days_remaining,
                'pro_subscription_id' => $sub->id,
                'pro_recurring_date' => $client->plan_recurring_date,
            ];
            $duedate = Carbon::now()->addDays(5);
            if ($days_remaining < 5) {
                $duedate = $client->plan_recurring_date;
            }

            $invoice_no = $pay->generateInvoice(client: $client, items: $items, duedate: $duedate);
            return redirect('user/invoices/view/' . $invoice_no)
                ->with('message', 'Please pay this invoice before due date so that you can opt in.');
        }
        $sub->opted_out_date = null;
        $sub->sub_status = 'Active';
        $sub->opted_out = 'No';
        $sub->save();
        return back()->with('message', 'You have opted in successfully');
    }

    public function optOut(Request $request, $id)
    {
        $request->validate([
            'otp' => 'required|numeric',
            'opt_out_when' => 'required',
        ]);

        $client = Client::find(auth('team')->user()->cl_id);
        $sub = ProSubscription::where('cl_id', auth('team')->user()->cl_id)->where('id', $id)->first();

        if (!$sub) {
            return back()->withErrors('Subscripton not found');
        }

        $checkOtp = checkOtp(auth('team')->user()->client, $request->otp);


        if (!$checkOtp) {
            return back()->withErrors('The OTP has eiter expired, used or does not exist');
        }

        $sub->opted_out = 'Yes';
        if ($request->opt_out_when == 'now') {
            $sub->sub_status = 'Inactive';
            $sub->opted_out_date = date('Y-m-d');
        } else {
            if ($client->plan_recurring_date < date('Y-m-d')) {
                $sub->opted_out_date = date('Y-m-d');
                $sub->sub_status = 'Inactive';
            } else {
                $sub->opted_out_date = $client->plan_recurring_date;
            }
        }

        $sub->save();
        return back()->with('message', 'You have opted out successfully');
    }

    public function generateInvoice($id)
    {
        $client = Client::where('id', auth('team')->user()->cl_id)
            ->where('plan_status', 'Active')->first();

        if (!$client) {
            return back()->withErrors("You don't have an active main subscription plan.");
        }
        $sub = ProSubscription::where('id', $id)->where('cl_id', auth('team')->user()->cl_id)->first();

        if (!$sub) {
            return back()->withErrors("Subscription not found");
        }

        $today = date('Y-m-d');

        if ($client->plan_status == 'Inactive') {
            return back()->withErrors("You can't generate invoice when your main subscription is Inactive.");
        }

        $expiryDate = Carbon::parse(Carbon::createFromFormat('Y-m-d', $client->plan_recurring_date)->endOfDay());
        $today = Carbon::now()->startOfDay();

        $days_remaining = ceil($expiryDate->diffInHours($today) / 24);

        if ($days_remaining < 2) {
            return back()->withErrors("You can't add pro subscription since your main subscription has expired or is expiring today. 
            You need to renew your main subscription. If you face difficult, you can contact support");
        }

        $proplan = ProPlan::where('status', 'active')
            ->where('plan_id', $client->plan_id)->first();

        if (!$proplan) {
            return back()->withErrors('The plan is either Inactive or does belong to your main subscription plan');
        }



        $expiryDate = Carbon::parse(Carbon::createFromFormat('Y-m-d', $client->plan_recurring_date)->endOfDay());
        $today = Carbon::now()->startOfDay();

        $days_remaining = ceil($expiryDate->diffInHours($today) / 24);
        if ($days_remaining > 30) {
            $days_remaining = 30;
        }

        if ($days_remaining < 2) {
            return back()->withErrors("You can't generate invoice since your main subscription has expired or is expiring today. 
            You need to renew your main subscription. If you face difficult, you can contact support");
        }

        $invoice = new PaymentInvoiceController();
        $items[] = ['plan' => $proplan, 'quantity' => 1, 'pro_subscription_id' => $sub->id, 'plan_recurring_date' => $client->plan_recurring_date];
        $expiryDate = Carbon::parse(Carbon::createFromFormat('Y-m-d', $client->plan_recurring_date)->endOfDay());
        $invoice_no = $invoice->generateInvoiceRemaining($client, $proplan, $items, $expiryDate);

        return redirect('user/invoices/view/' . $invoice_no)
            ->with('message', 'Please pay this invoice before due date.Please remember you need to have active main Scribble subscription for this invoice to be effective.');
    }




    public function integrationIncomplete()
    {
        return view('client.pro.sub.integration-incomplete');
    }
    public function mpesaIntegrationGuide()
    {
        return view('client.pro.mpesa-integration-guide');
    }
}