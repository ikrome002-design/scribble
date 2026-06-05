<?php

namespace App\Http\Controllers\Admin\Team;

use App\Http\Controllers\Controller;
use App\Models\TeamPlan;
use Illuminate\Http\Request;
use App\Plan;

class PlanController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $packages = TeamPlan::all();
        return view('admin.team.plan.index', compact('packages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $plans = Plan::all();

        return view('admin.team.plan.create', compact('plans'));
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
            'price' => 'required|numeric',
            'transaction_fee' => 'required|numeric',
            'discount_amount' => 'required|numeric',
            'govt_charges_amount' => 'required|numeric',
            'name' => 'required',
            'status' => 'required',
            'plan' => 'required',
        ]);
        $checkPlan = TeamPlan::where('plan_id', $request->plan);

        if ($checkPlan->count() > 0) {
            return back()->withErrors('The pricing model you are trying to add for the selected package or account already exists. Please review the existing pricing models to ensure there are no duplications.');
        }

        $teamplan = new TeamPlan();
        $teamplan->name = $request->name;
        $teamplan->price =  $request->price;
        $teamplan->transaction_fee = $request->transaction_fee;
        $teamplan->discount_type    = $request->disc_amount_charge;
        $teamplan->apply_discount = $request->discounts;
        $teamplan->discount_amount = $request->discount_amount;
        $teamplan->govt_charges_type    = $request->government_charges_type;
        $teamplan->apply_govt_charges = $request->apply_govt_charges;
        $teamplan->govt_charges_amt = $request->govt_charges_amount;
        $teamplan->plan_id = $request->plan;
        $teamplan->digital_tax = $request->digital_tax;
        $teamplan->price = $request->price;
        $teamplan->status = $request->status;
        $teamplan->notes = nl2br($request->notes);
        $teamplan->save();
        return redirect('/team/plan')->with('message', 'Plan added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TeamPlan  $teamplan
     * @return \Illuminate\Http\Response
     */
    public function show(TeamPlan $teamplan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TeamPlan  $teamplan
     * @return \Illuminate\Http\Response
     */
    public function edit(TeamPlan $plan)
    {
        $teamplan = $plan;
        $plans = Plan::all();

        return view('admin.team.plan.edit', compact('plans', 'teamplan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Teamlan  $teamplan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TeamPlan $plan)
    {
        $plans = Plan::all();
        $teamplan = $plan;

        $request->validate([
            'price' => 'required|numeric',
            'transaction_fee' => 'required|numeric',
            'discount_amount' => 'required|numeric',
            'govt_charges_amount' => 'required|numeric',
            'name' => 'required',
            'plan' => 'required',
            'status' => 'required'
        ]);
        $checkPlan = TeamPlan::where('plan_id', $request->plan)->where('id', '!=', $teamplan->id);
        if ($checkPlan->count() > 0) {
            return back()->withErrors('Sorry, Another team pricing model exist for the main plan');
        }

        $teamplan->name = $request->name;
        $teamplan->price =  $request->price;
        $teamplan->transaction_fee = $request->transaction_fee;
        $teamplan->discount_type    = $request->disc_amount_charge;
        $teamplan->apply_discount = $request->discounts;
        $teamplan->discount_amount = $request->discount_amount;
        $teamplan->govt_charges_type    = $request->government_charges_type;
        $teamplan->apply_govt_charges = $request->apply_govt_charges;
        $teamplan->govt_charges_amt = $request->govt_charges_amount;
        $teamplan->plan_id = $request->plan;
        $teamplan->digital_tax = $request->digital_tax;
        $teamplan->price = $request->price;
        $teamplan->status = $request->status;
        $teamplan->notes = nl2br($request->notes);
        $teamplan->save();

        return back()->with('message', 'Plan updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TeamPlan  $teamplan
     * @return \Illuminate\Http\Response
     */
    public function destroy(TeamPlan $plan)
    {
        $plan->delete();
        return back()->with('message', 'Plan deleted successfully');
    }
}
