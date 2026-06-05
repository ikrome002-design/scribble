<?php

namespace App\Http\Controllers\Admin\Pro;

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

        return $dataTable->render('admin.pro.staff.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        $subs = ProSubscription::where('cl_id', $staff->cl_id)
            ->where('sub_status', 'Active')->get();

        return   view('admin.pro.staff.edit', compact('staff', 'roles', 'subs'));
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

        $staff_check = Staff::where('cl_id', $staff->cl_id)
            ->where('email', $request->email)
            ->where('id', '!=', $staff->id)->first();

        if ($staff_check) {
            return back()->withInput()->withErrors("Another staff with the email exist");
        }

        $sub = ProSubscription::find($request->main_station);
        if (!$sub) {
            return back()->withInput()->withErrors("You selected a wrong working station or subscription has expired.");
        }





        $staff->email = $request->email;
        $staff->fname = $request->first_name;
        $staff->lname = $request->last_name;
        $staff->mname = $request->middle_name;
        $staff->cl_id = $staff->cl_id;
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
    public function destroy(Staff $staff)
    {

        $staff->delete();

        return back()->with('message', 'Staff deleted successfully');
    }

    public function deleteAll(Request $request)
    {
        $staff_ids = str_split(str_replace(' ', '', $request->staff_ids));

        $staff = Staff::whereIn('id', $staff_ids);
        foreach ($staff->get() as $s) {
            Storage::disk('private')->delete('staff/' . $s->image);
        }
        $staff->delete();

        return back()->with('message', 'All selected staff deleted successfully');
    }
}