<?php

namespace App\Http\Controllers\Client\Pro;

use App\Http\Controllers\Controller;
use App\Models\ProSubscription;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\StaffVisitorRole;
use App\Mail\General;
use Illuminate\Support\Facades\Storage;
use App\DataTables\StaffDataTable;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(StaffDataTable $dataTable)
    {

        return $dataTable->with('cl_id', auth('client')->user()->id)
            ->render('client.pro.staff.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $subs = ProSubscription::where('cl_id', auth('client')->user()->id)
            ->where('sub_status', 'Active')->get();
        if ($subs->count() == 0) {
            return back()->withErrors("You don't have any subscription which is active");
        }
        return view('client.pro.staff.create', compact('subs'));
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
            'last_name' => 'required',
            'phone_number' => 'required',
            'otp' => 'required|numeric',
            'role' => 'required',
            'email' => 'required',
            'gender' => 'required',
            'image' => 'mimes:jpg,png,jpeg,gif|max:5000',
            'main_station' => 'required',
        ]);
        $staff = Staff::where('cl_id', auth('client')->user()->id)
            ->where('email', $request->email)->first();

        if ($staff) {
            return back()->withInput()->withErrors('Already staff with the email exist.');
        }

        $sub = ProSubscription::where('cl_id', auth('client')->user()->id)
            ->where('id', $request->main_station)
            ->where('sub_status', 'Active')->first();
        if (!$sub) {
            return back()->withInput()->withErrors("You selected a wrong working station or  subscription has expired");
        }


        $checkOtp = checkOtp(auth('client')->user(), $request->otp);


        if (!$checkOtp) {
            return back()->withInput()->withErrors('The OTP has eiter expired, used or does not exist');
        }

        $filename = null;

        if ($request->image) {
            $filename = basename(Storage::disk('private')->put('staff', $request->image));
        }

        $staff = new Staff();
        $staff->email = $request->email;
        $staff->fname = $request->first_name;
        $staff->lname = $request->last_name;
        $staff->mname = $request->middle_name;
        $staff->cl_id = auth('client')->user()->id;
        $staff->id_number = $request->id_number;
        $staff->phone_number = $request->phone_number;
        $staff->image = $filename;
        $staff->gender = $request->gender;
        $staff->status = $request->status;
        $staff->role = $request->role;
        $staff->pro_subscription_id = $sub->id;
        $staff->save();



        $otp = generateOtp($staff, 30, false);
        $name = $staff->fname . ' ' . $staff->lname;
        $subject = 'Added As a Staff';
        $url = '//staff.' . env('APP_DOMAIN') . '/change-password';
        $message = "You have been added as new staff at " . $sub->business_name . ". Click the link below and login to create a new password. Please keep in mind you will be using  these unique Id and email as part of credentials every time you want sign in or change password.";
        $userData = ['Unique Id' => $staff->unique_id, 'email' => $request->email, 'OTP' => $otp];
        Mail::to($request->email)->send(new General($subject, $name, $message, $url, null, null, null, $userData));
        return redirect('/staff')->with('message', "Staff added successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function show(Staff $staff)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function edit(Staff $staff)
    {
        $roles = ['Manager', 'Attendant', 'Cashier', 'Security', ' Security Personnel'];
        $subs = ProSubscription::where('cl_id', auth('client')->user()->id)
            ->where('sub_status', 'Active')->get();
        if ($subs->count() == 0) {
            return back()->withErrors("You don't have any subscription which is active");
        }

        return   view('client.pro.staff.edit', compact('staff', 'roles', 'subs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Staff $staff)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone_number' => 'required',
            'role' => 'required',
            'email' => 'required',
            'gender' => 'required',
            'main_station' => 'required',
            'image' => 'mimes:jpg,png,jpeg,gif|max:5000'
        ]);

        $staff_check = Staff::where('cl_id', auth('client')->user()->id)
            ->where('email', $request->email)
            ->where('id', '!=', $staff->id)->first();

        if ($staff_check) {
            return back()->withInput()->withErrors("Another staff with the email exist");
        }

        $sub = ProSubscription::where('cl_id', auth('client')->user()->id)
            ->where('id', $request->main_station)
            ->where('sub_status', 'Active')->first();
        if (!$sub) {
            return back()->withInput()->withErrors("You selected a wrong working station or subscription has expired.");
        }


        $staff = Staff::where('cl_id', auth('client')->user()->id)
            ->where('id', '=', $staff->id)->first();

        if (!$staff) {
            return back()->withInput()->withErrors("The staff does not exist.");
        }


        $staff->email = $request->email;
        $staff->fname = $request->first_name;
        $staff->lname = $request->last_name;
        $staff->mname = $request->middle_name;
        $staff->cl_id = auth('client')->user()->id;
        $staff->id_number = $request->id_number;
        $staff->phone_number = $request->phone_number;

        if ($request->image) {
            $filename = basename(Storage::disk('private')->put('staff', $request->image));
            $staff->image = $filename;
        }

        $staff->gender = $request->gender;
        $staff->status = $request->status;
        $staff->role = $request->role;
        $staff->pro_subscription_id = $sub->id;
        $staff->save();

        return back()->with('message', 'Staff Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function destroy(Staff $staff, Request $request)
    {


        if ($staff->cl_id != auth('client')->user()->id) {
            return back()->withErrors('You are not allowed to delete this staff');
        }
        $checkOtp = checkOtp(auth('client')->user(), $request->otp);

        if (!$checkOtp) {
            return back()->withErrors('The OTP has eiter expired, used or does not exist');
        }
        $staff->delete();

        return back()->with('message', 'Staff deleted successfully');
    }

    public function deleteAll(Request $request)
    {

        if (!$request->otp || !$request->staff_ids) {
            return back()->withErrors('Either OTP missing or You did select all staff');
        }
        $staff_ids = str_split(str_replace(' ', '', $request->staff_ids));

        $staff = Staff::where('cl_id', auth('client')->user()->id)
            ->whereIn('id', $staff_ids);

        $checkOtp = checkOtp(auth('client')->user(), $request->otp);

        if (!$checkOtp) {
            return back()->withErrors('The OTP has eiter expired, used or does not exist');
        }

        foreach ($staff->get() as $s) {
            Storage::disk('private')->delete('staff/' . $s->image);
        }
        $staff->delete();

        return back()->with('message', 'All Staff deleted successfully');
    }

    public function workHistory(StaffDataTable $dataTable)
    {
        $work_history = true;
        return $dataTable->with([
            'cl_id' => auth('client')->user()->id,
            'work_history' => true
        ])
            ->render('client.pro.staff.index', compact('work_history'));
    }
}
