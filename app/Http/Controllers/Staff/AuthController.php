<?php

namespace App\Http\Controllers\Staff;

use App\Client;
use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Mail\General;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{


    public function changePassword(Request $request)
    {

        if ($request->isMethod('post')) {
            $request->validate([
                'otp' => 'required',
                'unique_id' => 'required',
                'email' => 'required|email',
                'password' => 'required|confirmed|min:6'

            ]);

            $staff = Staff::where('email', $request->email)->where('unique_id', $request->unique_id)->first();

            if (!$staff) {
                return back()->withInput()->withErrors('Either the unique ID or your email is wrong or does not exist');
            }

            if ($staff->status == 'Inactive') {
                return back()->withInput()->withErrors('You are not allowed to change password. Please contact your business owner.');
            }

            $checkOtp = checkOtp($staff, $request->otp);
            if (!$checkOtp) {
                return back()->withInput()->withErrors('The OTP has eiter expired, used or does not exist');
            }

            $password = Hash::make($request->password);
            $staff->password = $password;
            $staff->save();

            return redirect('/')->with('message', 'You changed passsword successfully');
        }

        return view('staff.change-password');
    }

    public function forgotPassword(Request $request)
    {

        $request->validate([
            'unique_id' => 'required',
            'email' => 'required|email',

        ]);

        $staff = Staff::where('email', $request->email)->where('unique_id', $request->unique_id)->first();

        if (!$staff) {
            return back()->withErrors('Either the unique ID or your email is wrong or does not exist');
        }

        if ($staff->status == 'Inactive') {
            return back()->withErrors('You are not allowed to login. Please contact your business owner.');
        }

        if ($staff->first_login == 0) {
            return back()->withErrors('Please contact business owner or staff responsible to send otp to change password.');
        }

        $name = $staff->fname . ' ' . $staff->lname;
        $subject = 'Change Password';
        $url = 'staff.' . env('APP_DOMAIN') . '/change-password';
        $message = "Please click the link below and use the following credentials to change password. The OTP expires after 10 minutes";


        $otp = generateOtp($staff, 30, false);
        $userData = ['Unique Id' => $staff->unique_id, 'email' => $staff->email, 'OTP' => $otp];
        Mail::to($request->email)->send(new General($subject, $name, $message, $url, 'Change Password', null, null, $userData));
        return redirect('change-password')->with('message', 'Check details to change your password sent to your email.');
    }

    public function login(Request $request)
    {

        if (auth('staff')->check()) {
            return redirect('/profile');
        }
        if ($request->isMethod('post')) {
            $request->validate([
                'unique_id' => 'required',
                'email' => 'required|email',
                'password' => 'required'

            ]);

            $staff = Staff::where('email', $request->email)->where('unique_id', $request->unique_id)->first();
            if (!$staff) {
                return back()->withErrors('Either the unique ID or your email is wrong or does not exist');
            }
            if ($staff->status == 'Inactive') {
                return back()->withErrors('You are not allowed to login. Please contact your business owner.');
            }

            $credentials = [
                'email' => $request->email,
                'password' => $request->password,
                'unique_id' => $request->unique_id
            ];
            if (auth('staff')->attempt($credentials, $request->remember)) {

                if (auth('staff')->user()->first_login == 0) {
                    $client = Client::find(auth('staff')->user()->cl_id);
                    $staff = Staff::find(auth('staff')->user()->id);
                    $name = $client->fname . ' ' . $client->lname;
                    $subject = 'First login for staff';
                    $message = 'The client with following detail as signed for the forst time to their staff account';
                    $user_data = [
                        'email' => $staff->email, 'ID Number' => $staff->id_number ?? 'N/A',
                        'unique_id' => $staff->unique_id, 'Phone Number' => $staff->phone_number
                    ];
                    Mail::to($client->email)->send(new General($subject, $name, $message, null, null, null, null, $user_data));
                    $staff->first_login = 1;
                    $staff->save();
                }
                return redirect('/profile');
            }

            return back()->withInput()->withErrors('Wrong credentials');
        }


        return view('staff.login');
    }

    public function logout()
    {
        auth('staff')->logout();
        return  redirect('/');
    }
}