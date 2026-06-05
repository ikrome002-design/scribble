<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Staff;

class ProfileController extends Controller
{


    //controller index page for pro 
    public function index()
    {
        $staff = Staff::find(auth('staff')->user()->id);
        return view('staff.profile', compact('staff'));
    }
}