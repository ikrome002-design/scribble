<?php

namespace App\Http\Controllers\Client\Pro;

use App\Models\VisitorBusiness;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProSubscription;
use App\DataTables\VisitorBusinessDataTable;
use Illuminate\Support\Facades\Log;

class VisitorBusinessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(VisitorBusinessDataTable $dataTable)
    {
        return $dataTable->with('cl_id', auth('client')->user()->id)
            ->render('client.pro.visitor.visitor-business.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $subs = ProSubscription::where('sub_status', 'Active')
            ->where('cl_id', auth('client')->user()->id)->get();
        return view('client.pro.visitor.visitor-business.create', compact('subs'));
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
            'subscription' => 'required',
            'business_name' => 'required',
        ]);
        $sub = ProSubscription::where('sub_status', 'Active')
            ->where('id', $request->subscription)
            ->where('cl_id', auth('client')->user()->id)->first();
        if (!$sub) {
            return back()->withErrors('Subscription not active or not found');
        }
        $b = new VisitorBusiness();
        $b->pro_subscription_id = $request->subscription;
        $b->business_name = $request->business_name;
        $b->save();
        return redirect('/visitorBusiness')->with('message', 'Business added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\VisitorBusiness  $visitorBusiness
     * @return \Illuminate\Http\Response
     */
    public function show(VisitorBusiness $visitorBusiness)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\VisitorBusiness  $visitorBusiness
     * @return \Illuminate\Http\Response
     */
    public function edit(VisitorBusiness $visitorBusiness)
    {
        $subs = ProSubscription::where('sub_status', 'Active')
            ->where('cl_id', auth('client')->user()->id)->get();
        return view('client.pro.visitor.visitor-business.edit', compact('subs', 'visitorBusiness'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\VisitorBusiness  $visitorBusiness
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VisitorBusiness $visitorBusiness)
    {
        $request->validate([
            'subscription' => 'required',
            'business_name' => 'required',
        ]);
        $sub = ProSubscription::where('sub_status', 'Active')
            ->where('id', $request->subscription)
            ->where('cl_id', auth('client')->user()->id)->first();
        if (!$sub) {
            return back()->withErrors('Subscription not active or not found');
        }

        $visitorBusiness->pro_subscription_id = $request->subscription;
        $visitorBusiness->business_name = $request->business_name;
        $visitorBusiness->save();
        return back()->with('message', 'Business updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\VisitorBusiness  $visitorBusiness
     * @return \Illuminate\Http\Response
     */
    public function destroy(VisitorBusiness $visitorBusiness)
    {
        $v = $visitorBusiness->whereHas('proSubscription', function ($q) {
            $q->where('cl_id', auth('client')->user()->id);
        })->first();
        if (!$v) {
            return back()->withErrors('Business not found');
        }
        $v->delete();
        return back()->with('message', 'Business successfully deleted');
    }

    public function businessAutofill(Request $request)
    {
        $items = VisitorBusiness::where('pro_subscription_id', $request->sub_id)
            ->whereHas('proSubscription', function ($q) {
                $q->where('cl_id', auth('client')->user()->id);
            })->get();
        return response()->json($items);
    }
}
