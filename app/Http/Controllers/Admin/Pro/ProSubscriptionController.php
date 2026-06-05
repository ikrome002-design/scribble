<?php

namespace App\Http\Controllers\Admin\Pro;

use App\Client;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PaymentInvoiceController;
use App\Models\ProPlan;
use App\Models\ProSubscription;
use App\Plan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Mail\General;
use App\SenderIdManage;
use App\DataTables\ProSubscriptionsDataTable;
use App\Models\ProSubscriptionFile;

class ProSubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProSubscriptionsDataTable $dataTable)
    {

        return $dataTable->render('admin.pro.sub.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.pro.sub.create');
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
            'client' => 'required',
            'generate_invoice' => "required",
            'business_name' => "required",
            'services' => 'required',
        ]);


        $client = Client::find($request->client);
        if ($client->plan_status == 'Inactive') {
            return back()->withErrors("The client does not have an active main subscription plan.");
        }

        if ($request->shortcode) {
            $pro = ProSubscription::where('shortcode', $request->shortcode)->first();
            if ($pro) {
                return back()->withInput()->withErrors('The shortcode is already been used for another business.');
            }
        }
        $proplan = ProPlan::where('status', 'active')
            ->where('plan_id', $client->plan_id)->first();

        if (!$proplan) {
            $main = Plan::find($client->plan_id)->name;
            return back()->withErrors("The plan is either Inactive or does belong to client's main subscription plan.
            The client belongs to $main plan.");
        }
        $today = date('Y-m-d');

        if ($client->plan_recurring_date < $today) {
            return back()->withErrors('There is problem with your main subscription expiration. Please count check in client manage section for next recurring date.');
        }

        $expiryDate = Carbon::parse(Carbon::createFromFormat('Y-m-d', $client->plan_recurring_date)->endOfDay());
        $today = Carbon::now()->startOfDay();
        $days_remaining = ceil($expiryDate->diffInHours($today) / 24);

        if ($days_remaining < 2) {
            return back()->withErrors("You can't add pro subscription since client's main subscription has expired or is expiring today. 
            The client need to renew your main subscription. The main expiration must be date must be greater than today");
        }


        $sub = new ProSubscription();
        $sub->cl_id = $request->client;
        $sub->shortcode = $request->shortcode;
        $sub->sub_status = $request->generate_invoice == 'Yes' ? 'Inactive' : 'Active';
        $sub->business_name = $request->business_name;
        $sub->shortcode_type = $request->shortcode_type;
        $sub->phone_number = $request->phone_number ?? $client->phone_number;
        $sub->summary_time = $request->summary_time ?? '23:00:00';
        $sub->plan_recurring_date = $request->generate_invoice == 'Yes' ? null :  $client->plan_recurring_date;
        foreach ($request->services as $r) {
            $sub->$r = 1;
        }
        $sub->save();

        if ($request->generate_invoice == 'Yes') {
            $invoice = new PaymentInvoiceController();
            $items[] = ['plan' => $proplan, 'quantity' => 1, 'pro_subscription_id' => $sub->id, 'plan_recurring_date' => $client->plan_recurring_date];
            $expiryDate = Carbon::parse(Carbon::createFromFormat('Y-m-d', $client->plan_recurring_date)->endOfDay());
            $invoice_no = $invoice->generateInvoiceRemaining($client, $proplan, $items, $expiryDate,);
        }
        return  redirect('/prosubscriptions')->with('message', "Subscription created successfully. Please remember to add sender id in edit section");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProSubscription  $prosubscription
     * @return \Illuminate\Http\Response
     */
    public function show(ProSubscription $prosubscription)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProSubscription  $prosubscription
     * @return \Illuminate\Http\Response
     */
    public function edit(ProSubscription $prosubscription)
    {
        $all_sender_id = SenderIdManage::where('status', 'unblock')->get();
        $all_ids = [];
        foreach ($all_sender_id as $sid) {
            $client_array = json_decode($sid->cl_id);

            if (isset($client_array) && is_array($client_array) && in_array('0', $client_array)) {
                array_push($all_ids, $sid->id);
            } elseif (isset($client_array) && is_array($client_array) && in_array($prosubscription->cl_id, $client_array)) {
                array_push($all_ids, $sid->id);
            }
        }
        $sender_ids = array_unique($all_ids);
        $sender_ids = SenderIdManage::whereIn('id', $sender_ids)->where('status', 'unblock')->get();

        if ($sender_ids->count() == 0) {
            return back()->withErrors("The client does not have an active sender id");
        }

        return view('admin.pro.sub.edit', compact('prosubscription', 'sender_ids'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProSubscription  $prosubscription
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProSubscription $prosubscription)
    {
        $this->validate($request, [
            'business_name' => "required",
            'opted_out' => "required",
            'sub_status' => "required",
            'services' => 'required',
            'sender_id' => 'required',
            'phone_number' => 'required',
        ]);

        $all_sender_id = SenderIdManage::where('status', 'unblock')->get();
        $all_ids = [];
        $client = Client::find($prosubscription->cl_id);
        foreach ($all_sender_id as $sid) {
            $client_array = json_decode($sid->cl_id);

            if (isset($client_array) && is_array($client_array) && in_array('0', $client_array)) {
                array_push($all_ids, $sid->id);
            } elseif (isset($client_array) && is_array($client_array) && in_array($client->id, $client_array)) {
                array_push($all_ids, $sid->id);
            }
        }
        $sender_ids = array_unique($all_ids);
        $sender = SenderIdManage::whereIn('id', $sender_ids)->where('status', 'unblock')
            ->where('id', $request->sender_id)->first();

        if (!$sender) {
            return back()->withErrors("The sender id is inactieve");
        }

        $today = date('Y-m-d');
        if ($request->sub_status == 'Active') {
            if ($client->plan_recurring_date < $today) {
                return back()->withErrors('There is problem with main subscription expiration date. Please count check in client manage section for next recurring date is today or greater than today.');
            }
        }

        $prosubscription->shortcode = $request->shortcode;
        $prosubscription->sub_status = $request->sub_status;
        $prosubscription->business_name = $request->business_name;
        $prosubscription->shortcode_status = $request->shortcode_status;
        $prosubscription->sender_id = $request->sender_id;
        $prosubscription->opted_out = $request->opted_out;
        $prosubscription->shortcode_type = $request->shortcode_type;
        $prosubscription->plan_recurring_date = $client->plan_recurring_date;
        $prosubscription->phone_number = $request->phone_number;
        $prosubscription->summary_time = $request->summary_time ?? '23:00:00';
        $prosubscription->staff = 0;
        $prosubscription->transactions = 0;
        $prosubscription->visitors = 0;
        foreach ($request->services as $r) {
            $prosubscription->$r = 1;
        }
        if ($request->opted_out == 'Yes') {
            $prosubscription->opted_out_date = date('Y-m-d');
        }

        $prosubscription->business_name = $request->business_name;
        $prosubscription->save();

        if ($request->email_client) {
            $client = Client::find($prosubscription->cl_id);
            $subject = 'Mpesa Integration Completed';
            $name = $client->fname . ' ' . $client->lname;
            $message = 'We are glad to inform that mpesa integration for shortcode ' . $request->shortcode . ' is complete.';

            Mail::to($client->email)->send(new General($subject, $name, $message));
        }

        return  back()->with('message', "Subscription updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProSubscription  $prosubscription
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProSubscription $prosubscription)
    {
        $prosubscription->delete();
        return  redirect('/prosubscriptions')->with('message', "Subscription deleted successfully");
    }

    public function fileDownload($file)
    {

        $path = storage_path('app/private/pro-subscription/' . $file);

        if (!File::exists($path)) {
            return back()->withErrors('File not found');
        }

        $check = ProSubscriptionFile::where('filename', $file)->first();

        if ($check) {
            $filename = $check->originalname;
        } else {
            $filename   =  'ProSubscriptionFile';
        }

        return Storage::disk('private')->download('pro-subscription/' . $check->filename, $filename);
    }
}
