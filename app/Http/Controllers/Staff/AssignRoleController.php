<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\ProSubscription;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\StaffStaffRole;
use App\Models\StaffVisitorRole;
use App\Mail\General;
use App\Models\StaffTransactionRole;
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
                'staff_id' => auth('staff')->user()->id
            ]
        )->render('staff.staff.assign-roles');
    }

    public function staffRoles($id, Request $request, ProSubscriptionsDataTable $dataTable)
    {

        $staff = Staff::where('id', '!=', auth('staff')->user()->id)->where('id', $id)
            ->whereHas('proSubscription', function ($q) {
                $q->whereHas('staffStaffRole', function ($q) {
                    $q->where('staff_id', auth('staff')->user()->id)
                        ->where('assign_roles', 1);
                });
            })->first();

        if (!$staff) {
            return back()->withErrors('You are not allow to re assign roles for this staff');
        }

        $subs = ProSubscription::where('sub_status', 'Active')
            ->whereHas('staffStaffRole', function ($q) {
                $q->where('staff_id', auth('staff')->user()->id)
                    ->where('assign_roles', 1);
            })->get();

        if ($subs->count() == 0) {
            return back()->withInput()->withErrors("Sorry! You don't have role to re assign roles for staff in any business or 
            business owner's subscription has expired.");
        }

        if ($request->isMethod('post')) {

            $roles = [];
            foreach ($subs as $s) {
                $add = $request->input('add' . $s->id) ? 1 : 0;
                $edit = $request->input('edit' . $s->id) ? 1 : 0;
                $delete = $request->input('delete' . $s->id) ? 1 : 0;
                $view = $request->input('view' . $s->id) ? 1 : 0;
                $assign_roles = $request->input('assign_roles' . $s->id) ? 1 : 0;

                $existing_roles = $s->staffStaffRole()->where('staff_id', $staff->id)->first();


                $otp = 0;

                $add_ex = 0;
                $edit_ex = 0;
                $delete_ex = 0;
                $view_ex = 0;
                $assign_roles_ex  = 0;

                $view_update = 0;
                $edit_update = 0;
                $add_update = 0;
                $delete_update = 0;
                $assign_roles_update = 0;


                if ($existing_roles) {
                    $add_ex = $existing_roles->add;
                    $edit_ex = $existing_roles->edit;
                    $delete_ex =  $existing_roles->delete;
                    $view_ex = $existing_roles->view;
                    $assign_roles_ex  =  $existing_roles->assign_role;
                }


                if ($s->staffStaffRole()->where('staff_id', auth('staff')->user()->id)->first()->view ?? 0) {
                    $view_update = $view;
                } else {
                    $view_update = $view_ex;
                }

                if ($s->staffStaffRole()->where('staff_id', auth('staff')->user()->id)->first()->add ?? 0) {
                    $add_update = $add;
                } else {
                    $add_update = $add_ex;
                }

                if ($s->staffStaffRole()->where('staff_id', auth('staff')->user()->id)->first()->edit ?? 0) {
                    $edit_update = $edit;
                } else {
                    $edit_update = $edit_ex;
                }

                if ($s->staffStaffRole()->where('staff_id', auth('staff')->user()->id)->first()->delete ?? 0) {
                    $delete_update = $delete;
                } else {
                    $delete_update = $delete_ex;
                }

                if ($s->staffStaffRole()->where('staff_id', auth('staff')->user()->id)->first()->assign_roles ?? 0) {
                    $assign_roles_update =  $assign_roles;
                } else {
                    $assign_roles_update =  $assign_roles_ex;
                }

                if ($add_update || $edit_update || $delete_update || $assign_roles_update) {
                    $view_update = 1;
                }
                if ($add_update || $edit_update || $delete_update) {
                    $otp = 1;
                }

                if ($add_update || $edit_update || $delete_update || $view_update || $assign_roles_update) {
                    $roles[] = [
                        'pro_subscription_id' => $s->id,
                        'staff_id' => $staff->id,
                        'add' => $add_update,
                        'edit' => $edit_update,
                        'delete' => $delete_update,
                        'view' => $view_update,
                        'assign_roles' => $assign_roles_update,
                        'otp' => $otp,
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
                'staff_id' => auth('staff')->user()->id,
                'staff' => $staff,
            ]
        )->render('staff.staff.staff', compact('staff'));
    }

    public function VisitorsRoles($id, Request $request, ProSubscriptionsDataTable $dataTable)
    {
        $staff = Staff::where('id', '!=', auth('staff')->user()->id)->where('id', $id)
            ->whereHas('proSubscription', function ($q) {
                $q->whereHas('staffVisitorRole', function ($q) {
                    $q->where('staff_id', auth('staff')->user()->id)
                        ->where('assign_roles', 1);
                });
            })->first();

        if (!$staff) {
            return back()->withErrors('You are not allow to re assign roles for this staff');
        }

        $subs = ProSubscription::where('sub_status', 'Active')
            ->whereHas('staffVisitorRole', function ($q) {
                $q->where('staff_id', auth('staff')->user()->id)
                    ->where('assign_roles', 1);
            })->get();

        if ($subs->count() == 0) {
            return back()->withInput()->withErrors("Sorry! You don't have role to re assign roles for staff in any business or 
            business owner's subscription has expired.");
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

                $existing_roles = $s->staffVisitorRole()->where('staff_id', $staff->id)->first();


                $add_ex = 0;
                $edit_ex = 0;
                $delete_ex = 0;
                $view_ex = 0;
                $check_out_ex = 0;
                $assign_roles_ex  = 0;


                $view_update = 0;
                $check_out_update = 0;
                $edit_update = 0;
                $add_update = 0;
                $delete_update = 0;
                $assign_roles_update = 0;

                if ($existing_roles) {
                    $add_ex = $existing_roles->add;
                    $edit_ex = $existing_roles->edit;
                    $delete_ex =  $existing_roles->delete;
                    $view_ex = $existing_roles->view;
                    $check_out_ex = $existing_roles->check_out;
                    $assign_roles_ex  =  $existing_roles->assign_role;
                }


                if ($s->staffVisitorRole()->where('staff_id', auth('staff')->user()->id)->first()->view ?? 0) {
                    $view_update = $view;
                } else {
                    $view_update = $view_ex;
                }


                if ($s->staffVisitorRole()->where('staff_id', auth('staff')->user()->id)->first()->check_out ?? 0) {
                    $check_out_update = $add;
                } else {
                    $check_out_update = $check_out_ex;
                }


                if ($s->staffVisitorRole()->where('staff_id', auth('staff')->user()->id)->first()->add ?? 0) {
                    $add_update = $add;
                } else {
                    $add_update = $add_ex;
                }

                if ($s->staffVisitorRole()->where('staff_id', auth('staff')->user()->id)->first()->edit ?? 0) {
                    $edit_update = $edit;
                } else {
                    $edit_update = $edit_ex;
                }

                if ($s->staffVisitorRole()->where('staff_id', auth('staff')->user()->id)->first()->delete ?? 0) {
                    $delete_update = $delete;
                } else {
                    $delete_update = $delete_ex;
                }

                if ($s->staffVisitorRole()->where('staff_id', auth('staff')->user()->id)->first()->assign_roles ?? 0) {
                    $assign_roles_update =  $assign_roles;
                } else {
                    $assign_roles_update =  $assign_roles_ex;
                }

                if ($add_update || $edit_update || $delete_update) {
                    $view_update = 1;
                }

                if ($assign_roles_update) {

                    if (!$s->staffStaffRole()->where('staff_id', $staff->id)->first()->view ?? 0) {
                        $error_staff_view = "You can't  assign this staff re assign role for $s->business_name. The staff does not have a role to view the staff in that business.";
                        break;
                    }
                }

                if ($add_update || $edit_update || $delete_update || $view_update || $check_out_update) {
                    $roles[] = [
                        'pro_subscription_id' => $s->id,
                        'staff_id' => $staff->id,
                        'add' => $add_update,
                        'edit' => $edit_update,
                        'delete' => $delete_update,
                        'view' => $view_update,
                        'check_out' => $check_out_update,
                        'assign_roles' => $assign_roles_update,
                    ];
                } else {
                    if ($s->staffVisitorRole()->where('staff_id', $staff->id)->count() > 0) {
                        $roles[] = [
                            'pro_subscription_id' => $s->id,
                            'staff_id' => $staff->id,
                            'add' => 0,
                            'edit' => 0,
                            'delete' => 0,
                            'view' => 0,
                            'otp' => 0,
                            'check_out' => 0,
                            'assign_roles' => 0,
                        ];
                    }
                }
            }

            if ($error_staff_view) {
                return back()->withErrors($error_staff_view);
            }

            if (count($roles) > 0) {
                StaffVisitorRole::upsert($roles, ['pro_subscription_id', 'staff_id']);
            }


            return back()->with('message', 'Staff roles for visitors updated successfully');
        }

        return $dataTable->with(
            [
                'assign_visitor_roles' => true,
                'staff_id' => auth('staff')->user()->id,
                'staff' => $staff,
            ]
        )->render('staff.staff.visitor', compact('staff'));
    }

    public function transactionsRoles($id, Request $request, ProSubscriptionsDataTable $dataTable)
    {
        $staff = Staff::where('id', '!=', auth('staff')->user()->id)->where('id', $id)
            ->whereHas('proSubscription', function ($q) {
                $q->whereHas('staffTransactionRole', function ($q) {
                    $q->where('staff_id', auth('staff')->user()->id)
                        ->where('assign_roles', 1);
                });
            })->first();

        if (!$staff) {
            return back()->withErrors('You are not allow to re assign roles for this staff');
        }

        $subs = ProSubscription::where('sub_status', 'Active')
            ->whereHas('staffTransactionRole', function ($q) {
                $q->where('staff_id', auth('staff')->user()->id)
                    ->where('assign_roles', 1);
            })->get();

        if ($subs->count() == 0) {
            return back()->withInput()->withErrors("Sorry! You don't have role to re assign roles for staff in any business or 
            business owner's subscription has expired.");
        }

        if ($request->isMethod('post')) {

            $roles = [];
            $error_staff_view = null;
            foreach ($subs as $s) {
                $last_24_hours = $request->input('last_24_hours' . $s->id) ? 1 : 0;
                $last_one_month = $request->input('last_one_month' . $s->id) ? 1 : 0;
                $all = $request->input('all' . $s->id) ? 1 : 0;
                $daily_summary = $request->input('dail_summary' . $s->id) ? 1 : 0;
                $monthly_summary = $request->input('monthly_summary' . $s->id) ? 1 : 0;
                $all_summary = $request->input('all_summary' . $s->id) ? 1 : 0;
                $transaction_sms = $request->input('transaction_sms' . $s->id) ? 1 : 0;
                $assign_roles = $request->input('assign_roles' . $s->id) ? 1 : 0;

                $existing_roles = $s->staffTransactionRole()->where('staff_id', $staff->id)->first();

                $last_24_hours_update =  0;
                $last_one_month_update =  0;
                $all_update = 0;
                $daily_summary_update = 0;
                $monthly_update =  0;
                $all_summary_update = 0;
                $transaction_sms_update = 0;
                $assign_roles_update = 0;

                $last_24_hours_ex = 0;
                $last_one_month_ex = 0;
                $all_ex = 0;
                $daily_summary_ex = 0;
                $monthly_summary_ex  = 0;
                $all_summary_ex = 0;
                $transaction_sms_ex = 0;
                $assign_roles_ex = 0;

                if ($existing_roles) {
                    $last_24_hours_ex = $existing_roles->last_24_hours;
                    $last_one_month_ex = $existing_roles->last_one_month;
                    $all_ex =  $existing_roles->all;
                    $daily_summary_ex = $existing_roles->daily_summary;
                    $monthly_summary_ex = $existing_roles->monthly_summary;
                    $all_summary_ex = $existing_roles->all_summary;
                    $transaction_sms_ex = $existing_roles->transaction_sms;
                    $assign_roles_ex  =  $existing_roles->assign_role;
                }


                if ($s->staffTransactionRole()->where('staff_id', auth('staff')->user()->id)->first()->last_24_hours ?? 0) {
                    $last_24_hours_update = $last_24_hours;
                } else {
                    $last_24_hours_update = $last_24_hours_ex;
                }

                if ($s->staffTransactionRole()->where('staff_id', auth('staff')->user()->id)->first()->last_one_month ?? 0) {
                    $last_one_month_update = $last_one_month;
                } else {
                    $add_update = $last_one_month_ex;
                }

                if ($s->staffTransactionRole()->where('staff_id', auth('staff')->user()->id)->first()->all ?? 0) {
                    $all_update = $all;
                } else {
                    $all_update = $all_ex;
                }

                if ($s->staffTransactionRole()->where('staff_id', auth('staff')->user()->id)->first()->daily_summary ?? 0) {
                    $daily_summary_update = $daily_summary;
                } else {
                    $daily_summary_update = $daily_summary_ex;
                }

                if ($s->staffTransactionRole()->where('staff_id', auth('staff')->user()->id)->first()->monthly_summary ?? 0) {
                    $monthly_summary_update =  $monthly_summary;
                } else {
                    $monthly_summary_update =  $monthly_summary_ex;
                }

                if ($s->staffTransactionRole()->where('staff_id', auth('staff')->user()->id)->first()->all_summary ?? 0) {
                    $monthly_summary_update =  $all_summary;
                } else {
                    $monthly_summary_update =  $all_summary_ex;
                }

                if ($s->staffTransactionRole()->where('staff_id', auth('staff')->user()->id)->first()->transaction_sms ?? 0) {
                    $transaction_sms_update =  $transaction_sms;
                } else {
                    $transaction_sms_update =  $transaction_sms_ex;
                }


                if ($s->staffTransactionRole()->where('staff_id', auth('staff')->user()->id)->first()->assign_roles ?? 0) {
                    $assign_roles_update =  $assign_roles;
                } else {
                    $assign_roles_update =  $assign_roles_ex;
                }

                if ($last_one_month_update) {
                    $last_24_hours_update = 1;
                }
                if ($all_update) {
                    $last_24_hours_update = 1;
                    $last_one_month_update = 1;
                }

                if ($monthly_summary_update) {
                    $dail_summary_update = 1;
                }
                if ($all_update) {
                    $last_24_hours_update = 1;
                    $last_one_month_update = 1;
                }

                if ($assign_roles_update) {

                    if (!$s->staffStaffRole()->where('staff_id', $staff->id)->first()->view ?? 0) {
                        $error_staff_view = "You can't  assign this staff re assign role for $s->business_name. The staff does not have a role to view the staff in that business.";
                        break;
                    }
                }


                if (
                    $last_24_hours_update || $last_one_month_update
                    || $all_update || $daily_summary_update
                    || $monthly_summary_update || $all_summary_update ||
                    $transaction_sms_update
                ) {
                    $roles[] = [
                        'pro_subscription_id' => $s->id,
                        'staff_id' => $staff->id,
                        'last_24_hours' => $last_24_hours_update,
                        'last_one_month' => $last_one_month_update,
                        'all' => $all_update,
                        'daily_summary' => $daily_summary_update,
                        'monthly_summary' => $monthly_summary_update,
                        'all_summary' => $all_summary_update,
                        'transaction_sms' => $transaction_sms_update,
                        'assign_roles' => $assign_roles_update,
                    ];
                } else {
                    if ($s->staffVisitorRole()->where('staff_id', $staff->id)->count() > 0) {
                        $roles[] = [
                            'pro_subscription_id' => $s->id,
                            'staff_id' => $staff->id,
                            'last_24_hours' => 0,
                            'last_one_month' => 0,
                            'all' => 0,
                            'daily_summary' => 0,
                            'monthly_summary' => 0,
                            'all_summary' => 0,
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
                'staff_id' => auth('staff')->user()->id,
                'staff' => $staff,
            ]
        )->render('staff.staff.transaction', compact('subs', 'staff'));
    }
}