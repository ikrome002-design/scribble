<?php

namespace App\Http\Controllers\Admin\Team;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\TeamMembersDataTable;
use App\Models\Staff;
use App\Models\TeamSubscription;
use Illuminate\Support\Facades\Storage;

class MembersController extends Controller
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
    public function index(TeamMembersDataTable $dataTable)
    {
        return $dataTable->render('admin.team.members.index');
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
     * @param  \App\Models\Staff  $member
     * @return \Illuminate\Http\Response
     */
    public function show(Staff $member)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Staff  $member
     * @return \Illuminate\Http\Response
     */
    public function edit(Staff $member)
    {
        $team_roles = ['Manager', 'Staff'];
        return view('admin.team.members.edit', compact('member', 'team_roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Staff  $member
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Staff $member)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone_number' => 'required',
            'email' => 'required',
            'is_team' => 'required',
            'gender' => 'required',
            'image' => 'mimes:jpg,png,jpeg,gif|max:5000',
        ]);


        $staff_check = Staff::where('cl_id', $member->id)
            ->where('email', $request->email)
            ->where('id', '!=', $member->id)->first();

        if ($staff_check) {
            return back()->withErrors("Another staff have that email for this client");
        }
        $sub = TeamSubscription::where('cl_id', $member->cl_id)->first();
        //check maximum number don't active
        if ($member->status == "Inactive" && $request->status == 'Active') {
            $initialStaff = Staff::where('status', 'Active');
            $count = $initialStaff->count() + 1;
            if ($count > $sub->team_members) {
                return back()->withErrors("You can have maximum of  $sub->team_members staff that  not opted out or active for this client.");
            }
        }
        //check maximum number don't  opted  in
        if ($member->team_opted_out == "Yes" && $request->team_opted_out == 'No') {

            $initialStaff = Staff::where('team_opted_out', 'No');
            $count = $initialStaff->count() + 1;
            if ($count > $sub->team_members) {
                return back()->withErrors("You can have maximum of  $sub->team_members staff that  not opted out or active.");
            }
        }

        $filename = null;

        if ($request->image) {
            $filename = basename(Storage::disk('private')->put('staff', $request->image));
            $member->image = $filename;
        }

        if ($request->team_opted_out == 'Yes' && $member->team_opted_out_date == null) {
            $member->team_opted_out_date = $sub->team_recurring_date;
        }
        if ($request->team_opted_out == 'No') {
            $member->team_opted_out_date = null;
        }
        $member->email = $request->email;
        $member->fname = $request->first_name;
        $member->lname = $request->last_name;
        $member->mname = $request->middle_name;
        $member->phone_number = $request->phone_number;
        $member->team_opted_out = $request->team_opted_out;
        $member->is_team = $request->is_team;
        $member->gender = $request->gender;
        $member->status = $request->status;
        $member->team_role = $request->team_role;
        $member->save();

        return back()->with('message', "Team member updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Staff  $member
     * @return \Illuminate\Http\Response
     */
    public function destroy(Staff $member)
    {
        Storage::disk('private')->delete('staff/' . $member->image);
        $member->delete();
        return back()->withErrors('Staff deleted successfully');
    }
}
