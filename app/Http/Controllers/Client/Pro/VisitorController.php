<?php

namespace App\Http\Controllers\Client\Pro;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use Illuminate\Http\Request;
use App\DataTables\VisitorsDataTable;
use App\Models\ProSubscription;
use Illuminate\Support\Facades\Storage;
use App\DataTables\ProSubscriptionsDataTable;
use App\Client;
use App\Helpers\SmsHelper;
use App\Models\Staff;
use App\Models\VisitorBusiness;

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
                'cl_id' => auth('client')->user()->id,
                'check_in_start_date' => $request->check_in_start_date,
                'check_in_end_date' => $request->check_in_end_date,
                'check_out_start_date' => $request->check_out_start_date,
                'check_out_end_date' => $request->check_out_end_date,
            ]
        )->render('client.pro.visitor.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {


        $sub = ProSubscription::where('cl_id', auth('client')->user()->id)
            ->where('id', $request->sub_id)
            ->where('sub_status', 'Active')->first();
        $subs = ProSubscription::where('cl_id', auth('client')->user()->id)
            ->where('sub_status', 'Active')->get();
        return view('client.pro.visitor.create', compact('subs', 'sub'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'phone_number' => 'required',
            'last_name' => 'required',
            'business_name' => 'required',
            'check_in_time' => 'required',
            'image' => 'mimes:jpg,png,jpeg,gif|max:5000'
        ]);


        $bus = VisitorBusiness::where('id', $request->business_name)
            ->whereHas('proSubscription', function ($q) {
                $q->where('sub_status', 'Active')
                    ->where('cl_id', auth('client')->user()->id);
            })->first();
        if (!$bus) {
            return back()->withErrors('The subscription for business name is not active or does not exist');
        }
        $filename = null;

        if ($request->image) {
            $filename = basename(Storage::disk('private')->put('visitor', $request->image));
        }


        $v = new Visitor();
        $v->fname = $request->first_name;
        $v->lname = $request->last_name;
        $v->pro_subscription_id = $bus->pro_subscription_id;
        $v->visitor_business_id = $bus->id;
        $v->image = $filename;
        $v->id_number = $request->id_number;
        $v->phone_number = $request->phone_number;
        $v->check_in_time = $request->check_in_time;
        $v->notes = $request->notes;
        $v->save();
        $main = $bus->proSubscription->business_name;
        $client = Client::find(auth('client')->user()->id);
        $message = "Welcome to $main ($bus->business_name) , $request->first_name! We are thrilled to have you visit us today. Please let us know if there is anything, we can do to make your stay more enjoyable.";
        $sms = new SmsHelper();
        $sms->clientSendQuickSms($bus->proSubscription->sender_id, $client, $request->phone_number, $message, 0, $bus->proSubscription->id);

        return redirect('/visitors')->with('message', 'Visitor added successfully');
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
        $subs = ProSubscription::where('cl_id', auth('client')->user()->id)
            ->where('sub_status', 'Active')->get();
        $buses = VisitorBusiness::where('pro_subscription_id', $visitor->pro_subscription_id)->get();

        if (!$visitor) {
            return back()->withError('visitor not found');
        }
        return view('client.pro.visitor.edit', compact('subs', 'visitor', 'buses'));
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


        $bus = VisitorBusiness::where('id', $request->business_name)
            ->whereHas('proSubscription', function ($q) {
                $q->where('sub_status', 'Active')
                    ->where('cl_id', auth('client')->user()->id);
            })->first();
        if (!$bus) {
            return back()->withErrors('The subscription for business name is not active or does not exist');
        }

        if ($request->image) {
            Storage::disk('private')->delete('visitor/' . $visitor->image);
            $filename = basename(Storage::disk('private')->put('visitor', $request->image));
            $visitor->image = $filename;
        }

        $visitor->fname = $request->first_name;
        $visitor->lname = $request->last_name;
        $visitor->visitor_business_id = $bus->id;
        $visitor->pro_subscription_id = $bus->pro_subscription_id;
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

        $visitor = Visitor::where('id', $id)
            ->whereHas('proSubscription', function ($q) {
                $q->where('sub_status', 'Active')
                    ->where('cl_id', auth('client')->user()->id);
            })->first();
        if (!$visitor) {
            return back()->withErrors('The subscription for business name is not active or does not exist');
        }

        $bus = VisitorBusiness::find($visitor->visitor_business_id);

        $visitor->check_out_time = $request->check_out_time;
        $visitor->save();

        $main = $bus->proSubscription->business_name;
        $client = Client::find(auth('client')->user()->id);
        $message = "Thank you for visiting $main ($bus->business_name) , $visitor->fname! We hope you had a pleasant experience and we look forward to serving you again soon.";
        $sms = new SmsHelper();
        $sms->clientSendQuickSms($bus->proSubscription->sender_id, $client, $visitor->phone_number, $message, 10, $bus->proSubscription->id);
        return back()->with('message', 'Checked out successfully.');
    }

    public function businesses(ProSubscriptionsDataTable $dataTable)
    {
        return $dataTable->with(
            [
                'cl_id' => auth('client')->user()->id,
                'visitors_per_business' => true,
            ]
        )->render('client.pro.per-business');
    }

    public function visitorsPerBusiness(VisitorsDataTable $dataTable, Request $request, $id)
    {


        $sub = ProSubscription::where('id', $id)
            ->where('sub_status', 'Active')
            ->where('cl_id', auth('client')->user()->id)->first();

        if (!$sub) {
            return back()->withErrors('Either no allowed or subscription is inactive');
        }

        return $dataTable->with(
            [
                'cl_id' => auth('client')->user()->id,
                'check_in_start_date' => $request->check_in_start_date,
                'check_in_end_date' => $request->check_in_end_date,
                'check_out_start_date' => $request->check_out_start_date,
                'check_out_end_date' => $request->check_out_end_date,
                'sub_id' => $sub->id,
            ]
        )->render('client.pro.visitor.index', compact('sub'));
    }

    public function workHistory(VisitorsDataTable $dataTable, Request $request, $work_staff_id)
    {
        $staff = Staff::where('id', $work_staff_id)
            ->where('cl_id', auth('client')->user()->id)
            ->first();
        if (!$staff) {
            return back()->withErrors('Staff Not found');
        }

        return $dataTable->with(
            [
                'cl_id' => auth('client')->user()->id,
                'work_staff_id' => $work_staff_id,
                'check_in_start_date' => $request->check_in_start_date,
                'check_in_end_date' => $request->check_in_end_date,
                'check_out_start_date' => $request->check_out_start_date,
                'check_out_end_date' => $request->check_out_end_date,
            ]
        )->render('client.pro.visitor.index', compact('staff'));
    }


    public function vistorsPerBusinessMinor(VisitorsDataTable $dataTable, Request $request, $business_id)
    {


        $bus = VisitorBusiness::where('id', $business_id)
            ->where('cl_id', auth('client')->user()->id)
            ->first();
        if (!$bus) {
            return back()->withErrors('not allowed');
        }
        return $dataTable->with(
            [
                'cl_id' => auth('client')->user()->id,
                'business_id' => $business_id,
                'check_in_start_date' => $request->check_in_start_date,
                'check_in_end_date' => $request->check_in_end_date,
                'check_out_start_date' => $request->check_out_start_date,
                'check_out_end_date' => $request->check_out_end_date,
            ]
        )->render('client.pro.visitor.index', compact('bus'));
    }
}
