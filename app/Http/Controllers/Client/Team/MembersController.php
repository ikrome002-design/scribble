<?php

namespace App\Http\Controllers\Client\Team;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;
use App\DataTables\TeamMembersDataTable;
use App\Models\TeamSubscription;
use App\Http\Controllers\PaymentInvoiceController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\General;
use App\Models\TeamPlan;
use Carbon\Carbon;

class MembersController extends Controller
{
    public function __construct()
    {

        $this->middleware('client');
        $this->middleware('clientTeamSubscription');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TeamMembersDataTable $dataTable)
    {
        return $dataTable->with('cl_id', auth('client')->user()->id)->render('client.team.members.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $team_roles = ['Manager', 'Staff'];
        return view('client.team.members.create', compact('team_roles'));
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
            // 'otp' => 'required|numeric',
            'team_role' => 'required',
            'email' => 'required',
            'gender' => 'required',
            'image' => 'mimes:jpg,png,jpeg,gif|max:5000',
        ]);


        $member = Staff::where('cl_id', auth('client')->user()->id)
            ->where('email', $request->email)->first();

        if ($member) {
            return back()->withInput()->withErrors('Already there is a staff with that email.');
        }

        $initialStaff = Staff::where('team_opted_out', 'No')->orWhere('status', 'Active');
        $sub = TeamSubscription::where('cl_id',  auth('client')->user()->id)->first();
        if ($initialStaff->count() >= $sub->team_members) {
            return back()->withErrors("You can have maximum of  $sub->team_members staff that are active or not opted out");
        }

        $checkOtp = checkOtp(auth('client')->user(), $request->otp);

        if (!$checkOtp) {
            return back()->withInput()->withErrors('The OTP has either expired, used or does not exist');
        }


        $member = new Staff();
        if ($request->image) {
            $filename = basename(Storage::disk('private')->put('staff', $request->image));
            $member->image = $filename;
        }
        $member->email = $request->email;
        $member->fname = $request->first_name;
        $member->lname = $request->last_name;
        $member->mname = $request->middle_name;
        $member->cl_id = auth('client')->user()->id;
        $member->phone_number = $request->phone_number;
        $member->is_team = 'Yes';
        $member->team_role = $request->team_role;
        $member->gender = $request->gender;
        $member->status = $request->status;
        $member->save();



        $otp = generateOtp($member, 30, false);
        $name = $member->fname . ' ' . $member->lname;
        $client_name = auth('client')->user()->lname . ' ' . auth('client')->user()->fname;
        $subject = "Invitation to Join  $client_name's Scribble Account as a [Role] .";
        $url = '//staff.' . env('APP_DOMAIN') . '/change-password';
        $message = [
            "We are delighted to extend our invitation for you to join $client_name's Scribble account as a $member->team_role. This opportunity will enable you to actively participate in our collaborative efforts and leverage the features offered by Scribble.",
            "We are thrilled to have you join us as a valued member of the Citrus Labs Experience community. We eagerly anticipate working together and creating remarkable experiences. If you encounter any challenges during the registration process or have any inquiries, please don't hesitate to contact our dedicated support team at hello@citruslabs.co.ke.",
            "Thank you for accepting the invitation to join $client_name's Scribble account as a $member->team_role. We eagerly anticipate the valuable contributions you will make as we embark on this journey together!",
            "Click the link below and login to create a new password. Please keep in mind you will be using these unique Id and email as part of credentials every time you want sign in or change password.",
        ];
        $userData = ['Unique Id' => $member->unique_id, 'email' => $request->email, 'OTP' => $otp];
        Mail::to($request->email)->send(new General($subject, $name, $message, $url, null, null, null, $userData));
        return redirect('team/members')->with('message', "Team members added successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Staff  $member
     * @return \Illuminate\Http\Response
     */
    public function show(Staff $member)
    {
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
        return view('client.team.members.edit', compact('member', 'team_roles'));
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

        if ($member->cl_id != auth('client')->user()->id) {
            return back()->withErrors('Not allowed');
        }
        $staff_check = Staff::where('cl_id', auth('client')->user()->id)
            ->where('email', $request->email)
            ->where('id', '!=', $member->id)->first();

        if ($staff_check) {
            return back()->withInput()->withErrors("Another staff have that email");
        }
        $sub = TeamSubscription::where('cl_id', auth('client')->user()->id)->first();
        //check maximum number don't active
        if ($member->status == "Inactive" && $request->status == 'Active') {
            $initialStaff = Staff::where('status', 'Active');
            $count = $initialStaff->count() + 1;
            if ($count > $sub->team_members) {
                return back()->withErrors("You can have maximum of  $sub->team_members staff that  not opted out or active.");
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
        $check = $member->where('cl_id', auth('client')->user()->id)->first();
        if (!$check) {
            return back()->withErrors('Staff member not found');
        }
        Storage::disk('private')->delete('staff/' . $member->image);
        $check->delete();
        return back()->withErrors('Staff deleted successfully');
    }
}
