<?php

namespace App\Http\Controllers\Admin\Pro;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use Illuminate\Http\Request;
use App\DataTables\VisitorsDataTable;
use App\Models\ProSubscription;
use Illuminate\Support\Facades\Storage;
use App\Helpers\SmsHelper;
use App\Client;

class VisitorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(VisitorsDataTable $dataTable, Request $request)
    {

        return $dataTable->with(
            [
                'check_in_start_date' => $request->check_in_start_date,
                'check_in_end_date' => $request->check_in_end_date,
                'check_out_start_date' => $request->check_out_start_date,
                'check_out_end_date' => $request->check_out_end_date,
            ]
        )->render('admin.pro.visitor.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {


        // $sub = ProSubscription::where('id', $request->sub_id)
        //     ->where('sub_status', 'Active')->first();
        // $subs = ProSubscription::where('sub_status', 'Active')->get();
        // return view('admin.pro.visitor.create', compact('subs', 'sub'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $request->validate([
        //     'first_name' => 'required',
        //     'phone_number' => 'required',
        //     'last_name' => 'required',
        //     'business_name' => 'required',
        //     'check_in_time' => 'required',
        //     'image' => 'mimes:jpg,png,jpeg,gif|max:5000'
        // ]);


        // $sub = ProSubscription::where('sub_status', 'Active')
        //     ->where('id', $request->business_name)->first();
        // if (!$sub) {
        //     return back()->withErrors('The subscription for business name is not active or does not exist');
        // }
        // $filename = null;

        // if ($request->image) {
        //     $filename = basename(Storage::disk('private')->put('visitor', $request->image));
        // }

        // $v = new Visitor();
        // $v->fname = $request->first_name;
        // $v->lname = $request->last_name;
        // $v->pro_subscription_id = $sub->id;
        // $v->image = $filename;
        // $v->id_number = $request->id_number;
        // $v->phone_number = $request->phone_number;
        // $v->check_in_time = $request->check_in_time;
        // $v->notes = $request->notes;
        // $v->save();

        // if ($request->check_in_messaage) {
        //     $client = Client::find($sub->cl_id);
        //     $message = "Welcome to $sub->business_name, $request->first_name! We are thrilled to have you visit us today. Please let us know if there is anything, we can do to make your stay more enjoyable.";
        //     $sms = new SmsHelper();
        //     $sms->clientSendQuickSms($sub->sender_id, $client, $request->phone_number, $message, 10);
        // }

        // return redirect('/visitors')->with('message', 'Visitor added successfully');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Visitor  $visitor
     * @return \Illuminate\Http\Response
     */
    public function show(Visitor $visitor)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Visitor  $visitor
     * @return \Illuminate\Http\Response
     */
    public function edit(Visitor $visitor)
    {
        $subs = ProSubscription::where('sub_status', 'Active')
            ->where('cl_id', $visitor->proSubscription->cl_id)->get();
        return view('admin.pro.visitor.edit', compact('subs', 'visitor'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Visitor  $visitor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Visitor $visitor)
    {
        $request->validate([
            'first_name' => 'required',
            'phone_number' => 'required',
            'last_name' => 'required',
            'business_name' => 'required',
            'check_in_time' => 'required',
            'image' => 'mimes:jpg,png,jpeg,gif|max:5000'
        ]);


        $sub = ProSubscription::where('sub_status', 'Active')
            ->where('id', $request->business_name)->first();
        if (!$sub) {
            return back()->withErrors('The subscription for business name is not active or does not exist');
        }

        if ($request->image) {
            Storage::disk('private')->delete('visitor/' . $visitor->image);
            $filename = basename(Storage::disk('private')->put('visitor', $request->image));
            $visitor->image = $filename;
        }

        $visitor->fname = $request->first_name;
        $visitor->lname = $request->last_name;
        $visitor->pro_subscription_id = $sub->id;
        $visitor->id_number = $request->id_number;
        $visitor->check_in_time = $request->check_in_time;
        $visitor->check_out_time = $request->check_out_time;
        $visitor->phone_number = $request->phone_number;
        $visitor->notes = $request->notes;
        $visitor->save();

        return back()->with('message', 'Visitor updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Visitor  $visitor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Visitor $visitor)
    {

        if (!$visitor) {
            return back()->withError('visitor not found');
        }

        $sub = ProSubscription::where('sub_status', 'Active')
            ->where('id', $visitor->pro_subscription_id)->first();
        if (!$sub) {
            return back()->withErrors('The subscription for business is not active or does not exist');
        }
        Storage::disk('private')->delete('visitor/' . $visitor->image);
        $visitor->delete();
        return back()->with('message', 'Visitor deleted successfully');
    }

    public function checkOutVisitor(Request $request, $id)
    {
        $request->validate([
            'check_out_time' => 'required',
        ]);
        $visitor = Visitor::find($id);

        if (!$visitor) {
            return back()->withErrors('The visitor does not exist');
        }

        $sub = ProSubscription::where('sub_status', 'Active')
            ->where('id', $visitor->pro_subscription_id)->first();
        if (!$sub) {
            return back()->withErrors('The subscription for business is not active or does not exist');
        }


        $visitor->check_out_time = $request->check_out_time;
        $visitor->save();
        $client = Client::find($sub->cl_id);
        $message = "Thank you for visiting $sub->business_name , $visitor->fname! We hope you had a pleasant experience and we look forward to serving you again soon.";
        $sms = new SmsHelper();
        $sms->clientSendQuickSms($sub->sender_id, $client, $visitor->phone_number, $message, 10, $sub->id);


        return back()->with('message', 'Checked out successfully.');
    }



    public function businesses()
    {


        $subs = ProSubscription::whereHas('staffTransactionRole', function ($q) {
            $q->where('last_24_hours', 1)
                ->orWhere('last_one_month', 1)
                ->orWhere('all', 1)
                ->where('daily_summary', 1)
                ->orWhere('monthly_summary', 1)
                ->orWhere('all_summary', 1);
        })->get();

        if ($subs->count() == 0) {
            return redirect('/dashboard')->withErrors('You are not allowed to view any transaction.');
        }

        return view('client..pro.visitor.businesses', compact('subs'));
    }

    public function visitorsPerBusiness(VisitorsDataTable $dataTable, Request $request, $id)
    {


        $sub = ProSubscription::where('id', $id)
            ->where('sub_status', 'Active')->first();

        if (!$sub) {
            return back()->withErrors('Either no allowed or subscription is inactive');
        }

        return $dataTable->with(
            [
                'check_in_start_date' => $request->check_in_start_date,
                'check_in_end_date' => $request->check_in_end_date,
                'check_out_start_date' => $request->check_out_start_date,
                'check_out_end_date' => $request->check_out_end_date,
                'sub_id' => $sub->id,
            ]
        )->render('admin.pro.visitor.index', compact('sub'));
    }
}