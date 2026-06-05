<?php


namespace App\Http\Controllers;

use App\Mail\General;
use App\Models\Staff;
use App\Models\StaffStaffRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\ProSubscription;

class OtpController extends Controller
{

    public function client(Request $request)
    {
        if (auth('client')->check()) {
            if ($request->staff_id) {
                $staff = Staff::where('cl_id', auth('client')->user()->id)
                    ->where('id', $request->staff_id)->first();
                if (!$staff) {
                    return  abort(404);
                }

                if ($request->sendFalse) {
                    $otp = generateOtp($staff, 30, false);
                    $name = $staff->fname . ' ' . $staff->lname;
                    $userData = ['Unique Id' => $staff->unique_id, 'email' => $staff->email, 'OTP' => $otp];
                    Mail::to($staff->email)->send(new General(
                        'OTP',
                        $name,
                        $request->message,
                        $request->link ?? $request->url,
                        $request->anchor,
                        null,
                        $request->files ?? [],
                        $request->userData ?? ($userData ?? [])
                    ));
                    return true;
                }
                return generateOtp($staff);
            }

            return generateOtp(auth('client')->user());
        }
    }

    public function staff(Request $request)
    {
        if (auth('staff')->check()) {
            if ($request->staff_id) {

                $staff = Staff::find($request->staff_id);
                if (!$staff) {
                    return abort(404);
                }
                if (auth('staff')->user()->staffStaffRole()->where('pro_subscription_id', $staff->pro_subscription_id)->first()->otp ?? 0) {

                    if ($request->sendFalse) {
                        $otp = generateOtp($staff, 30, false);
                        $name = $staff->fname . ' ' . $staff->lname;
                        $userData = ['Unique Id' => $staff->unique_id, 'email' => $staff->email, 'OTP' => $otp];
                        Mail::to($staff->email)->send(new General(
                            'OTP',
                            $name,
                            $request->message,
                            $request->link ?? $request->url,
                            $request->anchor,
                            null,
                            $request->files ?? [],
                            $request->userData ?? ($userData ?? [])
                        ));
                        return;
                    }
                    generateOtp($staff);
                    return;
                }
            }

            return generateOtp(auth('staff')->user());
        }
    }

    public function admin(Request $request)
    {
        if (auth('admin')->check()) {
            if ($request->staff_id) {
                $staff = Staff::find($request->staff_id);
                if (!$staff) {
                    return  abort(404);
                }

                if ($request->sendFalse) {
                    $otp = generateOtp($staff, 30, false);
                    $name = $staff->fname . ' ' . $staff->lname;
                    $userData = ['Unique Id' => $staff->unique_id, 'email' => $staff->email, 'OTP' => $otp];
                    Mail::to($staff->email)->send(new General(
                        'OTP',
                        $name,
                        $request->message,
                        $request->link ?? $request->url,
                        $request->anchor,
                        null,
                        $request->files ?? [],
                        $request->userData ?? ($userData ?? [])
                    ));
                    return true;
                }
                return generateOtp($staff);
            }
        }
    }
}