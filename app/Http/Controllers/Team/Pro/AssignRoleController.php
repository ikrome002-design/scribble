<?php

namespace App\Http\Controllers\Team\Pro;

use App\Http\Controllers\Controller;
use App\Models\ProSubscription;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\StaffStaffRole;
use App\Models\StaffVisitorRole;
use App\Mail\General;
use App\Models\StaffTransactionRole;
use Illuminate\Support\Facades\Storage;
use App\DataTables\StaffDataTable;
use App\DataTables\ProSubscriptionsDataTable;

class AssignRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(StaffDataTable $dataTable)
    {

        return $dataTable->with(
            [
                'assign_roles' => true,
                'cl_id' => auth('team')->user()->cl_id
            ]
        )->render('client.pro.staff.assign-roles');
    }

    public function staffRoles($id, Request $request, ProSubscriptionsDataTable $dataTable)
    {

        $subs = ProSubscription::where('cl_id', auth('team')->user()->cl_id)
            ->where('sub_status', 'Active')->get();
        if ($subs->count() == 0) {
            return back()->withErrors("You don't have any subscription which is active");
        }
        $staff = Staff::where('id', $id)->where('cl_id', auth('team')->user()->cl_id)->first();

        if (!$staff) {
            return back()->withErrors('Staff not found');
        }

        if ($request->isMethod('post')) {

            $roles = [];
            foreach ($subs as $s) {
                $add = $request->input('add' . $s->id) ? 1 : 0;
                $edit = $request->input('edit' . $s->id) ? 1 : 0;
                $delete = $request->input('delete' . $s->id) ? 1 : 0;
                $view = $request->input('view' . $s->id) ? 1 : 0;
                $assign_roles = $request->input('assign_roles' . $s->id) ? 1 : 0;
                $otp = 0;

                if ($add || $edit || $delete) {
                    $otp = 1;
                }

                if ($add || $edit || $delete || $assign_roles) {
                    $view = 1;
                }

                if ($add || $edit || $delete || $view || $assign_roles) {
                    $roles[] = [
                        'pro_subscription_id' => $s->id,
                        'staff_id' => $staff->id,
                        'add' => $add,
                        'edit' => $edit,
                        'delete' => $delete,
                        'view' => $view,
                        'otp' => $otp,
                        'assign_roles' => $assign_roles,
                    ];
                } else {
                    if ($s->staffStaffRole()->where('staff_id', $staff->id)->count() > 0) {
                        $roles[] = [
                            'pro_subscription_id' => $s->id,
                            'staff_id' => $staff->id,
                            'add' => 0,
                            'edit' => 0,
                            'delete' => 0,
                            'view' => 0,
                            'otp' => 0,
                            'assign_roles' => 0,
                        ];
                    }
                }
            }

            if (count($roles) > 0) {
                StaffStaffRole::upsert($roles, ['pro_subscription_id', 'staff_id']);
            }

            return back()->with('message', 'Staff roles for staff updated successfully');
        }
        return $dataTable->with(
            [
                'assign_staff_roles' => true,
                'cl_id' => auth('team')->user()->cl_id,
                'staff' => $staff,
            ]
        )->render('client.pro.staff.staff', compact('staff'));
    }

    public function visitorsRoles($id, Request $request, ProSubscriptionsDataTable $dataTable)
    {

        $subs = ProSubscription::where('cl_id', auth('team')->user()->cl_id)
            ->where('sub_status', 'Active')->get();
        if ($subs->count() == 0) {
            return back()->withErrors("You don't have any subscription which is active");
        }
        $staff = Staff::where('id', $id)->where('cl_id', auth('team')->user()->cl_id)->first();

        if (!$staff) {
            return back()->withErrors('Staff no found');
        }

        if ($request->isMethod('post')) {
            $roles = [];
            $error_staff_view = null;
            foreach ($subs as $s) {
                $add = $request->input('add' . $s->id) ? 1 : 0;
                $edit = $request->input('edit' . $s->id) ? 1 : 0;
                $delete = $request->input('delete' . $s->id) ? 1 : 0;
                $view = $request->input('view' . $s->id) ? 1 : 0;
                $check_out = $request->input('check_out' . $s->id) ? 1 : 0;
                $assign_roles = $request->input('assign_roles' . $s->id) ? 1 : 0;

                if ($edit || $delete) {
                    $check_out = 1;
                }

                if ($add || $edit || $delete || $assign_roles || $check_out) {
                    $view = 1;
                }

                if ($assign_roles) {

                    if (!$view) {
                        $error_staff_view = "You can't  assign this staff re assign role for $s->business_name. The staff does not have a role to view the staff in that business.";
                        break;
                    }
                }

                if ($add || $edit || $delete || $view || $check_out) {
                    $roles[] = [
                        'pro_subscription_id' => $s->id,
                        'staff_id' => $staff->id,
                        'add' => $add,
                        'edit' => $edit,
                        'delete' => $delete,
                        'view' => $view,
                        'check_out' => $check_out,
                        'assign_roles' => $assign_roles,
                    ];
                } else {
                    $roles[] = [
                        'pro_subscription_id' => $s->id,
                        'staff_id' => $staff->id,
                        'add' => 0,
                        'edit' => 0,
                        'delete' => 0,
                        'view' => 0,
                        'check_out' => 0,
                        'assign_roles' => 0,
                    ];
                }
            }

            if ($error_staff_view) {
                return back()->withErrors($error_staff_view);
            }

            if (count($roles) > 0) {
                StaffVisitorRole::upsert($roles, ['pro_subscription_id', 'staff_id']);
            }

            return back()->with('message', 'Staff roles for vistors updated successfully');
        }

        return $dataTable->with(
            [
                'assign_visitor_roles' => true,
                'cl_id' => auth('team')->user()->cl_id,
                'staff' => $staff,
            ]
        )->render('client.pro.staff.visitor', compact('staff'));
    }

    public function transactionsRoles($id, Request $request, ProSubscriptionsDataTable $dataTable)
    {

        $subs = ProSubscription::where('cl_id', auth('team')->user()->cl_id)
            ->where('sub_status', 'Active')->get();
        if ($subs->count() == 0) {
            return back()->withErrors("You don't have any subscription which is active");
        }

        $staff = Staff::where('id', $id)->where('cl_id', auth('team')->user()->cl_id)->first();

        if (!$staff) {
            return back()->withErrors('Staff no found');
        }

        if ($request->isMethod('post')) {
            $roles = [];
            $error_staff_view = null;
            foreach ($subs as $s) {
                $last_24_hours = $request->input('last_24_hours' . $s->id) ? 1 : 0;
                $last_one_month = $request->input('last_one_month' . $s->id) ? 1 : 0;
                $all = $request->input('all' . $s->id) ? 1 : 0;
                $daily_summary = $request->input('daily_summary' . $s->id) ? 1 : 0;
                $monthly_summary = $request->input('monthly_summary' . $s->id) ? 1 : 0;
                $all_summary = $request->input('all_summary' . $s->id) ? 1 : 0;
                $transaction_sms = $request->input('transaction_sms' . $s->id) ? 1 : 0;
                $add = $request->input('add' . $s->id) ? 1 : 0;
                $edit = $request->input('edit' . $s->id) ? 1 : 0;
                $delete = $request->input('delete' . $s->id) ? 1 : 0;
                $assign_roles = $request->input('assign_roles' . $s->id) ? 1 : 0;


                if ($last_one_month) {
                    $last_24_hours = 1;
                }
                if ($all) {
                    $daily_summary = 1;
                    $last_one_month = 1;
                }

                if ($monthly_summary) {
                    $daily_summary = 1;
                }

                if ($all_summary) {
                    $daily_summary = 1;
                    $monthly_summary = 1;
                }

                if ($assign_roles) {

                    if (!isset($s->staffStaffRole()->where('staff_id', $staff->id)->first()->view)) {
                        $error_staff_view = "You can't  assign this staff re assign role for $s->business_name. The staff does not have a role to view the staff in that business.";
                        break;
                    }
                }

                if (
                    $last_24_hours || $last_one_month || $all
                    || $daily_summary || $monthly_summary ||
                    $all_summary ||
                    $transaction_sms ||  $add || $edit || $delete
                ) {
                    $roles[] = [
                        'pro_subscription_id' => $s->id,
                        'staff_id' => $staff->id,
                        'last_24_hours' => $last_24_hours,
                        'last_one_month' => $last_one_month,
                        'all' => $all,
                        'daily_summary' => $daily_summary,
                        'monthly_summary' => $monthly_summary,
                        'all_summary' =>   $all_summary,
                        'transaction_sms' =>  $transaction_sms,
                        'add' => $add,
                        'edit' => $edit,
                        'delete' => $delete,
                        'assign_roles' => $assign_roles,
                    ];
                } else {
                    if ($s->staffTransactionRole()->where('staff_id', $staff->id)->count() > 0) {
                        $roles[] = [
                            'pro_subscription_id' => $s->id,
                            'staff_id' => $staff->id,
                            'last_24_hours' => 0,
                            'last_one_month' => 0,
                            'all' => $all,
                            'daily_summary' => 0,
                            'monthly_summary' => 0,
                            'all_summary' =>  0,
                            'add' => 0,
                            'edit' => 0,
                            'delete' => 0,
                            'transaction_sms' => 0,
                            'assign_roles' => 0,
                        ];
                    }
                }
            }


            if ($error_staff_view) {
                return back()->withErrors($error_staff_view);
            }

            if (count($roles) > 0) {
                StaffTransactionRole::upsert($roles, ['pro_subscription_id', 'staff_id']);
            }

            return back()->with('message', 'Staff roles for transactions updated successfully');
        }
        return $dataTable->with(
            [
                'assign_transaction_roles' => true,
                'cl_id' => auth('team')->user()->cl_id,
                'staff' => $staff,
            ]
        )->render('client.pro.staff.transaction', compact('staff'));
    }
}