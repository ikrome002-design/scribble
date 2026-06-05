<?php

namespace App\Http\Controllers\Client\Pro;

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
                'cl_id' => auth('client')->user()->id,
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
        $client = Client::where('id', auth('client')->user()->id)
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
            } elseif (isset($client_array) && is_array($client_array) && in_array(auth('client')->user()->id, $client_array)) {
                array_push($all_ids, $sid->id);
            }
        }
        $sender_ids = array_unique($all_ids);
        $sender_ids = SenderIdManage::whereIn('id', $sender_ids)->where('status', 'unblock')->get();

        if ($sender_ids->count() == 0) {
            return back()->withErrors("You don't an active sender id");
        }

        $today = date('Y-m-d');
        if ($client->plan_recurring_date < $today) {
            return back()->withErrors('There is problem with your main subscription expiration. Please contact support.');
        }

        $expiryDate = Carbon::parse(Carbon::createFromFormat('Y-m-d', $client->plan_recurring_date)->endOfDay());
        $today = Carbon::now()->startOfDay();

        $days_remaining = ceil($expiryDate->diffInHours($today) / 24);
        if ($days_remaining > 30) {
            $days_remaining = 30;
        }
        $remDaysFraction =  $days_remaining / 30;
        if ($days_remaining < 2) {
            return back()->withErrors("You can't add pro subscription since your main subscription has expired or is expiring today. 
            You need to renew your main subscription. If you face difficult, you can contact support");
        }

        return view('client.pro.sub.create', compact('proplan', 'days_remaining', 'sender_ids', 'remDaysFraction'));
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
        ]);



        if ($request->shortcode) {
            $pro = ProSubscription::where('shortcode', $request->shortcode)->first();
            if ($pro) {
                return back()->withInput()->withErrors('The shortcode is already been used for another business. Please contact support if you think it is error.');
            }
        }

        $client = Client::where('id', auth('client')->user()->id)
            ->where('plan_status', 'Active')->first();

        if (!$client) {
            return back()->withErrors("You don't have an active main subscription plan.");
        }

        $proplan = ProPlan::where('status', 'active')
            ->where('plan_id', $client->plan_id)->first();

        if (!$proplan) {
            return back()->withErrors('The plan is either Inactive or does belong to your main subscription plan');
        }


        $files = $request->upload_files;
        $disk = Storage::disk('private');

        $all_sender_id = SenderIdManage::where('status', 'unblock')->get();
        $all_ids = [];
        foreach ($all_sender_id as $sid) {
            $client_array = json_decode($sid->cl_id);

            if (isset($client_array) && is_array($client_array) && in_array('0', $client_array)) {
                array_push($all_ids, $sid->id);
            } elseif (isset($client_array) && is_array($client_array) && in_array(auth('client')->user()->id, $client_array)) {
                array_push($all_ids, $sid->id);
            }
        }
        $sender_ids = array_unique($all_ids);
        $sender = SenderIdManage::whereIn('id', $sender_ids)->where('status', 'unblock')
            ->where('id', $request->sender_id)->first();

        if ($sender->count() == 0) {
            return back()->withErrors("You don't an active sender id");
        }

        $today = date('Y-m-d');
        if ($client->plan_recurring_date < $today) {
            return back()->withErrors('There is problem with your main subscription expiration. Please contact support.');
        }

        $expiryDate = Carbon::parse(Carbon::createFromFormat('Y-m-d', $client->plan_recurring_date)->endOfDay());
        $today = Carbon::now()->startOfDay();

        $days_remaining = ceil($expiryDate->diffInHours($today) / 24);

        if ($days_remaining > 30) {
            $days_remaining = 30;
        }

        if ($days_remaining < 2) {
            return back()->withErrors("You can't add pro subscription since your main subscription has expired or is expiring today. 
            You need to renew your main subscription. If you face difficult, you can contact support");
        }

        $sub = new ProSubscription();
        $sub->cl_id = auth('client')->user()->id;

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
        $sub->summary_time = $request->summary_time ?? '23:00:00';
        foreach ($request->services as $r) {
            $sub->$r = 1;
        }
        $sub->save();

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

        $invoice = new PaymentInvoiceController();
        $items[] = [
            'plan' => $proplan,
            'quantity' => 1,
            'pro_subscription_id' => $sub->id,
            'plan_recurring_date' => $client->plan_recurring_date
        ];
        $expiryDate = Carbon::parse(Carbon::createFromFormat('Y-m-d', $client->plan_recurring_date)->endOfDay());
        $invoice_no = $invoice->generateInvoiceRemaining($client, $proplan, $items, $expiryDate,);

        $url = "//admin-pro." . env('APP_DOMAIN') . "/prosubscriptions/$sub->id/edit";
        $anchor = 'View Pro Subscription';
        $subject = 'New Pro Subscription';
        $message = 'You have new pro subscription. You can counter check if user has paid and follow up for mpesa integration if required';

        Mail::to(env('SUBSCRIPTIONS_EMAIL'))->send(new General($subject, 'Admin', $message, $url, $anchor));

        $name = auth('client')->user()->fname . ' ' . auth('client')->user()->lname;
        $message = [
            "Thank you for applying for Scribble PRO, the most advanced Bulk SMS service ever. We have received your application and we're excited to work on it as quickly as possible.",
            "Rest assured that we will notify you via email and Scribble notification as soon as your application is ready. In the meantime, if you have any questions or concerns, please don't hesitate to contact us by raising a ticket on the Scribble platform or by sending an email to scribble.support@citrus.co.ke.",
            "We would like to remind you that we will contact you on the given phone number $request->phone_number if we need any additional information from you.",
            "Thank you again for choosing Scribble PRO."
        ];
        $subject = "Scribble PRO Application Received";
        Mail::to(auth('client')->user()->email)->send(new General($subject, $name, $message));
        session(['new-pro-sub' => true]);
        return redirect('user/invoices/view/' . $invoice_no)
            ->with('message', 'Please pay this invoice before due date.Please remember you need to have active main Scribble subscription for this invoice to be effective.');
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
        return back()->withErrors('No allowed');
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
        return back()->withErrors('No allowed');
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
        $sub = ProSubscription::where('cl_id', auth('client')->user()->id)->where('id', $id)->first();
        if (!$sub) {
            return back()->withErrors('Subscription not found');
        }


        $client = Client::where('id', auth('client')->user()->id)
            ->where('plan_status', 'Active')->first();

        if (!$client) {
            return back()->withErrors("You don't have an active main subscription plan.");
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
            return back()->withErrors("You can't  opt in since your main subscription has expired or is expiring today. 
            You need to renew your main subscription first. If you face difficult, you can contact support");
        }
        $invoice = new PaymentInvoiceController();
        $items[] = ['plan' => $proplan, 'quantity' => 1, 'pro_subscription_id' => $sub->id, 'plan_recurring_date' => $client->plan_recurring_dat];
        $expiryDate = Carbon::parse(Carbon::createFromFormat('Y-m-d', $client->plan_recurring_date)->endOfDay());
        $invoice_no = $invoice->generateInvoiceRemaining($client, $proplan, $items, $expiryDate,);;
        return redirect('user/invoices/view/' . $invoice_no)
            ->with('message', 'Please pay this invoice before due date so that you can opt in.');
    }

    public function optOut(Request $request, $id)
    {
        $request->validate([
            'otp' => 'required|numeric',
            'opt_out_when' => 'required',
        ]);

        $client = Client::where('id', auth('client')->user()->id);
        $sub = ProSubscription::where('cl_id', auth('client')->user()->id)->where('id', $id)->first();

        if (!$sub) {
            return back()->withErrors('Subscripton not found');
        }

        $checkOtp = checkOtp(auth('client')->user(), $request->otp);


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
        $client = Client::where('id', auth('client')->user()->id)
            ->where('plan_status', 'Active')->first();

        if (!$client) {
            return back()->withErrors("You don't have an active main subscription plan.");
        }
        $sub = ProSubscription::where('id', $id)->where('cl_id', auth('client')->user()->id)->first();

        if (!$sub) {
            return back()->withErrors("Subscription not found");
        }

        $today = date('Y-m-d');

        if ($client->plan_recurring_date < $today) {
            return back()->withErrors('There is problem with your main subscription expiration. Please contact support.');
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
        $remDaysFraction =  $days_remaining / 30;
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
        return view('client.pro.integration-incomplete');
    }
    public function mpesaIntegrationGuide()
    {
        return view('client.pro.mpesa-integration-guide');
    }
}