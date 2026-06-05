<?php

namespace App\Http\Controllers\Admin\Pro;

use App\ClientGroups;
use App\Http\Controllers\Controller;
use App\Models\ProPlan;
use App\Plan;
use Illuminate\Http\Request;
use App\Helpers\PriceCalculation;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $packages = ProPlan::All();
        return view('admin.pro.plan.index', compact('packages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $plans = Plan::All();
        $client_group = ClientGroups::All();
        return view('admin.pro.plan.create', compact('plans', 'client_group'));
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
            'price' => 'required|numeric',
            'transaction_fee' => 'required|numeric',
            'discount_amount' => 'required|numeric',
            'govt_charges_amount' => 'required|numeric',
            'name' => 'required',
            'status' => 'required',
            'plan' => 'required|unique:pro_plans,id'
        ]);

        $calc = new PriceCalculation();
        $calc = $calc->calculatePrice(
            $request->price,
            $request->government_charges_Type,
            $request->govt_charges_amount,
            $request->discount_amount,
            $request->disc_amount_charge,
            $request->transaction_fee
        );

        $proplan = new ProPlan();
        $proplan->name = $request->name;
        $proplan->price =  $request->price;
        $proplan->transaction_fee = $request->transaction_fee;
        $proplan->discount_type    = $request->disc_amount_charge;
        $proplan->apply_discount = $request->discounts;
        $proplan->discount_amount = $request->discount_amount;
        $proplan->govt_charges_type    = $request->government_charges_type;
        $proplan->apply_govt_charges = $request->apply_govt_charges;
        $proplan->govt_charges_amt = $request->govt_charges_amount;
        $proplan->plan_id = $request->plan;
        $proplan->price = $request->price;
        $proplan->tax = $calc['tax'];
        $proplan->trans_amount = $calc['trans_amount'];
        $proplan->discount = $calc['discount'];
        $proplan->total = $calc['price'];
        $proplan->status = $request->status;
        $proplan->save();


        return to_route('proplans.index')->with([
            'message' => 'The plan was successfully added.',

        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProPlan  $proPlan
     * @return \Illuminate\Http\Response
     */
    public function show(ProPlan $proPlan)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProPlan  $proPlan
     * @return \Illuminate\Http\Response
     */
    public function edit(ProPlan $proplan)
    {

        $plans = Plan::All();
        $client_group = ClientGroups::All();

        return view('admin.pro.plan.edit', compact('plans', 'client_group', 'proplan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProPlan  $proPlan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProPlan $proplan)
    {
        $this->validate($request, [
            'price' => 'required|numeric',
            'transaction_fee' => 'required|numeric',
            'discount_amount' => 'required|numeric',
            'govt_charges_amount' => 'required|numeric',
            'name' => 'required',
            'status' => 'required',
        ]);
        $calc = new PriceCalculation();
        $calc = $calc->calculatePrice(
            $request->price,
            $request->government_Charges_Type,
            $request->govt_charges_amount,
            $request->discount_amount,
            $request->disc_amount_charge,
            $request->transaction_fee
        );

        $proplan->name = $request->name;
        $proplan->price =  $request->price;
        $proplan->transaction_fee = $request->transaction_fee;
        $proplan->discount_type    = $request->disc_amount_charge;
        $proplan->apply_discount = $request->discounts;
        $proplan->discount_amount = $request->discount_amount;
        $proplan->govt_charges_type    = $request->government_charges_type;
        $proplan->apply_govt_charges = $request->apply_govt_charges;
        $proplan->govt_charges_amt = $request->govt_charges_amount;
        $proplan->plan_id = $request->plan;
        $proplan->price = $request->price;
        $proplan->tax = $calc['tax'];
        $proplan->trans_amount = $calc['trans_amount'];
        $proplan->discount = $calc['discount'];
        $proplan->total = $calc['price'];
        $proplan->status = $request->status;
        $proplan->save();


        return back()->with([
            'message' => 'The plan was successfully updated.',

        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProPlan  $proPlan
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProPlan $proplan)
    {
        $proplan->delete();
        return back()->with([
            'message' => 'The plan was successfully deleted.',

        ]);
    }
}
