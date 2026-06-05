<?php

namespace App\Http\Controllers\Admin\Pro;



use App\Http\Controllers\Controller;
use App\Models\ProPlan;

class DashBoardController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }
    //controller index page for pro 
    public function dashboard()
    {
        return view('admin.pro.dashboard');
    }
}
