<?php

namespace App\Http\Controllers\Client\Pro;

use App\Http\Controllers\Controller;
use App\Models\ProPlan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $packages = ProPlan::where('status', 'Active')->get();
        return view('client.pro.plan.index', compact('packages'));
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
     * @param  \App\Models\ProPlan  $proPlan
     * @return \Illuminate\Http\Response
     */
    public function show(ProPlan $proPlan)
    {
       return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProPlan  $proPlan
     * @return \Illuminate\Http\Response
     */
    public function edit(ProPlan $proPlan)
    {
       return back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProPlan  $proPlan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProPlan $proPlan)
    {
       return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProPlan  $proPlan
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProPlan $proPlan)
    {
       return back();
    }
}