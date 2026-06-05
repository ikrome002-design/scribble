<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\ProSubscription;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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
        $add_roles = ProSubscription::where('sub_status', 'Active')
            ->whereHas('staffStaffRole', function ($q) {
                $q->where('staff_id', auth('staff')->user()->id)
                    ->where('add', 1);
            })->get();

        return $dataTable->with(
            'staff_id',
            auth('staff')->user()->id
        )
            ->render('staff.staff.index', compact('add_roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $subs = ProSubscription::where('sub_status', 'Active')
            ->whereHas('staffStaffRole', function ($q) {
                $q->where('staff_id', auth('staff')->user()->id)
                    ->where('add', 1);
            })->get();
        if ($subs->count() == 0) {
            return back()->withInput()->withErrors("Sorry! You don't have role to add staff to any business or 
            business owner's subscription has expired.");
        }
        return view('staff.staff.create', compact('subs'));
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
            'email' => 'required|email',
            'gender' => 'required',
            'image' => 'mimes:jpg,png,jpeg,gif|max:5000',
            'main_station' => 'required',
        ]);
        $staff = Staff::where('cl_id', auth('staff')->user()->cl_id)
            ->where('email', $request->email)->first();

        if ($staff) {
            return back()->withInput()->withErrors('Already staff with the email exist.');
        }

        $sub = ProSubscription::where('sub_status', 'Active')
            ->where('id', $request->main_station)
            ->whereHas('staffStaffRole', function ($q) {
                $q->where('staff_id', auth('staff')->user()->id)
                    ->where('add', 1);
            })->first();
        if (!$sub) {
            return back()->withErrors("Sorry! You don't have role to add staff to the business or 
            business owner's subscription has expired.");
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
        $staff->cl_id = auth('staff')->user()->cl_id;
        $staff->staff_id = auth('staff')->user()->id;
        $staff->id_number = $request->id_number;
        $staff->phone_number = $request->phone_number;
        $staff->image = $filename;
        $staff->gender = $request->gender;
        $staff->status = $request->status;
        $staff->role = $request->role;
        $staff->pro_subscription_id = $sub->id;
        $staff->save();


        $name = $staff->fname . ' ' . $staff->lname;
        $subject = 'Added As a Staff';
        $url = 'staff.' . env('APP_DOMAIN') . '/change-password';
        $message_active = "You have been added as a new staff at " . $sub->business_name . ". Click the link below and login to create a new password. Please keep in mind you will be using  these unique Id and email as part of credentials every time you want sign in or change password.";
        $message_inactive = "You have been added as a new staff at " . $sub->business_name . ". Here are your details.";

        if ($request->status == 'Active') {
            $otp = generateOtp($staff, 30, false);
            $userData = ['Unique Id' => $staff->unique_id, 'email' => $request->email, 'OTP' => $otp];
            Mail::to($request->email)->send(new General($subject, $name, $message_active, $url, null, null, null, $userData));
        } else {
            $otp = generateOtp($staff, 30, false);
            $userData = ['Unique Id' => $staff->unique_id, 'email' => $request->email];
            Mail::to($request->email)->send(new General($subject, $name, $message_inactive, null, null, null, null, $userData));
        }
        return redirect('staff')->with('message', "Staff added successfully");
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
        $subs = ProSubscription::where('sub_status', 'Active')
            ->whereHas('staffStaffRole', function ($q) {
                $q->where('staff_id', auth('staff')->user()->id)
                    ->where('edit', 1);
            })->get();

        $staff = Staff::where('id', $staff->id)->whereHas('proSubscription', function ($q) {
            $q->whereHas('staffStaffRole', function ($q) {
                $q->where('staff_id', auth('staff')->user()->id)
                    ->where('edit', 1);
            });
        })->first();

        if (!$staff) {
            return back()->withInput()->withErrors("You are not allowed edit this staff");
        }



        if ($subs->count() == 0) {
            return back()->withInput()->withErrors("Sorry! You don't have role to add staff to any business or 
            business owner's subscription has expired.");
        }

        return   view('staff.staff.edit', compact('staff', 'roles', 'subs'));
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
            'email' => 'required|email',
            'gender' => 'required',
            'main_station' => 'required',
            'image' => 'mimes:jpg,png,jpeg,gif|max:5000'
        ]);

        $staff_check = Staff::where('cl_id', auth('staff')->user()->cl_id)
            ->where('email', $request->email)
            ->where('id', '!=', $staff->id)->first();

        if ($staff_check) {
            return back()->withInput()->withErrors("Another staff with the email exist");
        }

        $sub = ProSubscription::where('sub_status', 'Active')
            ->where('id', $request->main_station)
            ->whereHas('staffStaffRole', function ($q) {
                $q->where('staff_id', auth('staff')->user()->id)
                    ->where('edit', 1);
            })->first();
        if (!$sub) {
            return back()->withErrors("Sorry! You don't have role to edit staff of the business or 
            business owner's subscription has expired.");
        }

        $staff = Staff::where('id', $staff->id)->whereHas('proSubscription', function ($q) {
            $q->whereHas('staffStaffRole', function ($q) {
                $q->where('staff_id', auth('staff')->user()->id)
                    ->where('edit', 1);
            });
        })->first();
        if (!$staff) {
            return back()->withErrors("You are not allowed edit this staff");
        }


        $staff->email = $request->email;
        $staff->fname = $request->first_name;
        $staff->lname = $request->last_name;
        $staff->mname = $request->middle_name;
        $staff->cl_id = auth('staff')->user()->cl_id;
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
        if ($staff->cl_id != auth('staff')->user()->cl_id) {
            return back()->withErrors('You are not allowed to delete this staff');
        }

        if ($staff->id == auth('staff')->user()->id) {
            return back()->withErrors('You are not allowed delete yourself.');
        }

        $staff = Staff::where('id', $staff->id)->whereHas('proSubscription', function ($q) {
            $q->whereHas('staffStaffRole', function ($q) {
                $q->where('staff_id', auth('staff')->user()->id)
                    ->where('delete', 1);
            });
        })->first();

        if (!$staff) {
            return back()->withErrors("You are not allowed delete this staff");
        }

        $checkOtp = checkOtp(auth('staff')->user(), $request->otp);

        if (!$checkOtp) {
            return back()->withErrors('The OTP has eiter expired, used or does not exist');
        }
        $staff->delete();

        return back()->with('message', 'Staff deleted successfully');
    }

    public function workHistory(StaffDataTable $dataTable)
    {
        $work_history = true;
        $add_roles = ProSubscription::where('sub_status', 'Active')
            ->whereHas('staffStaffRole', function ($q) {
                $q->where('staff_id', auth('staff')->user()->id)
                    ->where('add', 1);
            })->get();
        return $dataTable->with([
            'staff_id' => auth('staff')->user()->id,
            'work_history' => true
        ])
            ->render('staff.staff.index', compact('add_roles', 'work_history'));
    }
}