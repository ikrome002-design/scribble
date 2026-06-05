<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\ShortcodeTransaction;
use Illuminate\Http\Request;
use App\DataTables\ShortcodeTransactionDataTable;
use App\Models\ProSubscription;
use  Illuminate\Support\Carbon;
use App\DataTables\ProSubscriptionsDataTable;
use App\Models\Staff;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ShortcodeTransactionDataTable $dataTable)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        if ($request->start_date) {
            $start_date = Carbon::parse($request->start_date)->format('Y-m-d H:i:s');
        }

        if ($request->end_date) {
            $end_date = Carbon::parse($request->end_date)->format('Y-m-d H:i:s');
        }

        $startDay = Carbon::now()->startOfDay()->format('Y-m-d H:i:s');
        $endDay = Carbon::now()->endOfDay()->format('Y-m-d H:i:s');

        $start_month = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
        $end_month = Carbon::now()->endOfMonth()->format('Y-m-d H:i:s');

        $todaySales = ShortcodeTransaction::whereBetween('transaction_date', [$startDay, $endDay])
            ->whereHas('proSubscription', function ($q) {
                $q->where('sub_status', 'Active')
                    ->whereHas('staffTransactionRole', function ($q) {
                        $q->where('staff_id', auth('staff')->user()->id)
                            ->where('daily_summary', 1);
                    });
            })->sum('amount');
        $today_roles = ProSubscription::where('sub_status', 'Active')
            ->where('shortcode_status', 'Complete')
            ->where('shortcode', '!=', null)
            ->whereHas('staffTransactionRole', function ($q) {
                $q->where('staff_id', auth('staff')->user()->id)
                    ->where('daily_summary', 1);
            })->count();

        $monthSales = ShortcodeTransaction::whereBetween('transaction_date', [$start_month, $end_month])
            ->whereHas('proSubscription', function ($q) {
                $q->where('sub_status', 'Active')
                    ->whereHas('staffTransactionRole', function ($q) {
                        $q->where('staff_id', auth('staff')->user()->id)
                            ->where('monthly_summary', 1);
                    });
            })->sum('amount');

        $month_roles = ProSubscription::where('sub_status', 'Active')
            ->where('shortcode_status', 'Complete')
            ->where('shortcode', '!=', null)
            ->whereHas('staffTransactionRole', function ($q) {
                $q->where('staff_id', auth('staff')->user()->id)
                    ->where('monthly_summary', 1);
            })->count();

        $totalSales = ShortcodeTransaction::whereHas('proSubscription', function ($q) {
            $q->where('sub_status', 'Active')
                ->whereHas('staffTransactionRole', function ($q) {
                    $q->where('staff_id', auth('staff')->user()->id)
                        ->where('all_summary', 1);
                });
        })->sum('amount');

        $total_roles = ProSubscription::where('sub_status', 'Active')
            ->where('shortcode_status', 'Complete')
            ->where('shortcode', '!=', null)
            ->whereHas('staffTransactionRole', function ($q) {
                $q->where('staff_id', auth('staff')->user()->id)
                    ->where('all_summary', 1);
            })->count();

        $checkAll = ProSubscription::whereHas('staffTransactionRole', function ($q) {
            $q->where('staff_id', auth('staff')->user()->id)
                ->where('all', 1);
        })->get();

        if ($checkAll->count() == 0) {
            return redirect('transactions/per/business')->withErrors('You are not allowed to view all transactions');
        }


        return $dataTable->with(
            [
                'staff_id' => auth('staff')->user()->id,
                'start_date' => $start_date,
                'end_date' => $end_date,

            ]
        )->render(
            'staff.transaction.index',
            compact('todaySales', 'totalSales', 'monthSales', 'total_roles', 'month_roles', 'today_roles')
        );
    }

    public function businesses(ProSubscriptionsDataTable $dataTable)
    {


        $subs = ProSubscription::whereHas('staffTransactionRole', function ($q) {
            $q->where('staff_id', auth('staff')->user()->id)
                ->where('last_24_hours', 1)
                ->orWhere('last_one_month', 1)
                ->orWhere('all', 1)
                ->where('daily_summary', 1)
                ->orWhere('monthly_summary', 1)
                ->orWhere('all_summary', 1);
        })->get();

        if ($subs->count() == 0) {
            return redirect('/dashboard')->withErrors('You are not allowed to view any transaction.');
        }


        return $dataTable->with(
            [
                'staff_id' => auth('staff')->user()->id,
                'transactions_per_business' => true,

            ]
        )->render('staff.per-business');
    }

    public function transactionsPerBusiness(Request $request, ShortcodeTransactionDataTable $dataTable, $id)
    {


        $sub = ProSubscription::where('id', $id)
            ->where('sub_status', 'Active')
            ->where('shortcode_status', 'Complete')
            ->where('shortcode', '!=', null)
            ->whereHas('staffTransactionRole', function ($q) {
                $q->where('staff_id', auth('staff')->user()->id)
                    ->where('last_24_hours', 1)
                    ->orWhere('last_one_month', 1)
                    ->orWhere('all', 1)
                    ->where('daily_summary', 1)
                    ->orWhere('monthly_summary', 1)
                    ->orWhere('all_summary', 1);
            })->first();

        if (!$sub) {
            return back()->withErrors('Either no allowed or subscription is inactive or incomplete');
        }

        $start_date = null;
        $end_date = null;

        $now = Carbon::now()->format('Y-m-d H:i:s');

        $last_24_hours = Carbon::now()->subDay()->format('Y-m-d H:i:s');

        $last_one_month = Carbon::now()->subMonth()->format('Y-m-d H:i:s');

        if ($request->start_date) {
            $request_start_date = Carbon::parse($request->start_date)->format('Y-m-d H:i:s');
        }

        if ($request->end_date) {
            $request_end_date = Carbon::parse($request->end_date)->format('Y-m-d H:i:s');;
        }



        $startDay = Carbon::now()->startOfDay()->format('Y-m-d H:i:s');
        $endDay = Carbon::now()->endOfDay()->format('Y-m-d H:i:s');

        $start_month = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
        $end_month = Carbon::now()->endOfMonth()->format('Y-m-d H:i:s');

        $todaySales = ShortcodeTransaction::where('shortcode', $sub->shortcode)
            ->whereBetween('transaction_date', [$startDay, $endDay])
            ->whereHas('proSubscription', function ($q) {
                $q->where('sub_status', 'Active')
                    ->whereHas('staffTransactionRole', function ($q) {
                        $q->where('staff_id', auth('staff')->user()->id)
                            ->where('daily_summary', 1);
                    });
            })->sum('amount');
        $today_roles = ProSubscription::where('id', $id)
            ->where('sub_status', 'Active')
            ->where('shortcode_status', 'Complete')
            ->where('shortcode', '!=', null)
            ->whereHas('staffTransactionRole', function ($q) {
                $q->where('staff_id', auth('staff')->user()->id)
                    ->where('daily_summary', 1);
            })->count();

        $monthSales = ShortcodeTransaction::where('shortcode', $sub->shortcode)
            ->whereBetween('transaction_date', [$start_month, $end_month])
            ->whereHas('proSubscription', function ($q) {
                $q->where('sub_status', 'Active')
                    ->whereHas('staffTransactionRole', function ($q) {
                        $q->where('staff_id', auth('staff')->user()->id)
                            ->where('monthly_summary', 1);
                    });
            })->sum('amount');

        $month_roles = ProSubscription::where('id', $id)
            ->where('sub_status', 'Active')
            ->where('shortcode_status', 'Complete')
            ->where('shortcode', '!=', null)
            ->whereHas('staffTransactionRole', function ($q) {
                $q->where('staff_id', auth('staff')->user()->id)
                    ->orWhere('monthly_summary', 1);
            })->count();

        $totalSales = ShortcodeTransaction::where('shortcode', $sub->shortcode)
            ->whereHas('proSubscription', function ($q) {
                $q->where('sub_status', 'Active')
                    ->whereHas('staffTransactionRole', function ($q) {
                        $q->where('staff_id', auth('staff')->user()->id)
                            ->where('all_summary', 1);
                    });
            })->sum('amount');
        $total_roles = ShortcodeTransaction::where('shortcode', $sub->shortcode)
            ->whereHas('proSubscription', function ($q) {
                $q->where('sub_status', 'Active')
                    ->whereHas('staffTransactionRole', function ($q) {
                        $q->where('staff_id', auth('staff')->user()->id)
                            ->where('all_summary', 1);
                    });
            })->count();

        if ($sub->staffTransactionRole()->where('staff_id', auth('staff')->user()->id)->first()->all) {

            $start_date = $request->start_date;
            $end_date = $request->end_date;
        } else if ($sub->staffTransactionRole()->where('staff_id', auth('staff')->user()->id)->first()->last_one_month) {
            $start_date = $last_one_month;
            $end_date = $now;
            if ($request->start_date) {
                if ($request_start_date < $last_one_month || $request_start_date > $now) {
                    $start_date = $last_one_month;
                } else {
                    $start_date = $request_start_date;
                }
            }
            if ($request->end_date) {
                if ($request_end_date < $last_one_month || $request_end_date > $now) {
                    $end_date = $now;
                } else {
                    $end_date = $request_end_date;
                }
            }
        } else  if ($sub->staffTransactionRole()->where('staff_id', auth('staff')->user()->id)->first()->last_24_hours) {
            $start_date = $last_24_hours;
            $end_date = $now;
            if ($request->start_date) {
                if ($request_start_date < $last_24_hours || $request_start_date > $now) {
                    $start_date = $last_24_hours;
                } else {
                    $start_date = $request_start_date;
                }
            }
            if ($request->end_date) {
                if ($request_end_date > $now || $request_end_date < $last_24_hours) {
                    $end_date = $now;
                } else {
                    $end_date = $request_end_date;
                }
            }
        }
        return $dataTable->with(
            [
                'shortcode' => $sub->shortcode,
                'staff_id' => auth('staff')->user()->id,
                'start_date' => $start_date,
                'end_date' => $end_date,

            ]
        )
            ->render(
                'staff.transaction.index',
                compact('todaySales', 'totalSales', 'monthSales', 'total_roles', 'month_roles', 'today_roles')
            );
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
     * @param  \App\Models\ShortcodeTransaction  $shortcodeTransaction
     * @return \Illuminate\Http\Response
     */
    public function show(ShortcodeTransaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ShortcodeTransaction  $shortcodeTransaction
     * @return \Illuminate\Http\Response
     */
    public function edit(ShortcodeTransaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShortcodeTransaction  $shortcodeTransaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ShortcodeTransaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShortcodeTransaction  $shortcodeTransaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShortcodeTransaction $transaction)
    {
        //
    }

    public function workHistory(ShortcodeTransactionDataTable $dataTable, Request $request, $work_staff_id)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        if ($request->start_date) {
            $start_date = Carbon::parse($request->start_date)->format('Y-m-d H:i:s');
        }

        if ($request->end_date) {
            $end_date = Carbon::parse($request->end_date)->format('Y-m-d H:i:s');
        }
        $staff = Staff::where('id', $work_staff_id)
            ->whereHas('proSubscription', function ($q) {
                $q->where('sub_status', 'Active')
                    ->whereHas('staffStaffRole', function ($q) {
                        $q->where('staff_id', auth('staff')->user()->id)
                            ->where('view', 1);
                    });
            })->first();
        if (!$staff) {
            return back()->withErrors('Staff Not found or not allowed to view');
        }

        return $dataTable->with(
            [
                'staff_id' => auth('staff')->user()->id,
                'work_staff_id' => $staff->unique_id,
                'start_date' => $start_date,
                'end_date' => $end_date,
            ]
        )->render('staff.transaction.index', compact('staff'));
    }
}
