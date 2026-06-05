<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DefaultController extends Controller
{
    public function industry()
    {
        return view('client.add-select-industry');
    }

    public function addIndustry(Request $request)
    {
        $industry = $request->get('industry');
        return view('client.registration', compact('industry'));
    }
}
