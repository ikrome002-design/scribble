<?php

namespace App\Http\Controllers\Client\Team;

use App\Http\Controllers\Controller;
use App\Models\TeamPlan;
use Illuminate\Http\Request;
use App\Models\TeamSubscription;

class PlanController extends Controller
{
    public function __construct()
    {
        $this->middleware('client');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teamplans = TeamPlan::where('plan_id', auth('client')->user()->plan_id)
            ->where('status', 'Active')->get();
        $sub = TeamSubscription::where('cl_id', auth('client')->user()->id)->get();
        return view('client.team.plan.index', compact('teamplans', 'sub'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TeamPlan  $teamPlan
     * @return \Illuminate\Http\Response
     */
    public function show(TeamPlan $teamPlan)
    {
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TeamPlan  $teamPlan
     * @return \Illuminate\Http\Response
     */
    public function edit(TeamPlan $teamPlan)
    {
        return back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TeamPlan  $teamPlan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TeamPlan $teamPlan)
    {
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TeamPlan  $teamPlan
     * @return \Illuminate\Http\Response
     */
    public function destroy(TeamPlan $teamPlan)
    {
        return back();
    }
}
