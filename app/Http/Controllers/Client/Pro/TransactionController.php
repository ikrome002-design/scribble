<?php

namespace App\Http\Controllers\Client\Pro;

use App\Http\Controllers\Controller;
use App\Models\ShortcodeTransaction;
use Illuminate\Http\Request;
use App\DataTables\ShortcodeTransactionDataTable;
use App\DataTables\ProSubscriptionsDataTable;
use App\Models\ProSubscription;
use Illuminate\Support\Carbon;
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
                    ->where('cl_id', auth('client')->user()->id);
            })->sum('amount');

        $monthSales = ShortcodeTransaction::whereBetween('transaction_date', [$start_month, $end_month])
            ->whereHas('proSubscription', function ($q) {
                $q->where('sub_status', 'Active')
                    ->where('cl_id', auth('client')->user()->id);
            })->sum('amount');

        $totalSales = ShortcodeTransaction::whereHas('proSubscription', function ($q) {
            $q->where('sub_status', 'Active')
                ->where('cl_id', auth('client')->user()->id);
        })->sum('amount');



        return $dataTable->with(
            [
                'cl_id' => auth('client')->user()->id,
                'start_date' => $start_date,
                'end_date' => $end_date,

            ]
        )
            ->render('client.pro.transaction.index', compact('todaySales', 'totalSales', 'monthSales'));
    }
    public function businesses(ProSubscriptionsDataTable $dataTable)
    {


        return $dataTable->with(
            [
                'cl_id' => auth('client')->user()->id,
                'transactions_per_business' => true,

            ]
        )->render('client.pro.per-business');
    }

    public function transactionsPerBusiness($id, Request $request, ShortcodeTransactionDataTable $dataTable)
    {

        $sub = ProSubscription::where('id', $id)->where('cl_id', auth('client')->user()->id)->first();

        if (!$sub) {
            return back()->withErrors('The subscription not found');
        }

        if (!$sub->shortcode) {
            return back()->withErrors("You don't have shortcode to view transactions");
        }

        if ($sub->shortcode_status == 'Incomplete') {
            return redirect('/integration/incomplete');
        }

        if ($sub->sub_status == 'Inactive') {
            return back()->withErrors("Your subscription is not active");
        }


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
            ->where('shortcode', $sub->shortcode)->sum('amount');

        $monthSales = ShortcodeTransaction::whereBetween('transaction_date', [$start_month, $end_month])
            ->where('shortcode', $sub->shortcode)->sum('amount');

        $totalSales = ShortcodeTransaction::where('shortcode', $sub->shortcode)->sum('amount');



        return $dataTable->with(
            [
                'shortcode' => $sub->shortcode,
                'cl_id' => auth('client')->user()->id,
                'start_date' => $start_date,
                'end_date' => $end_date,
            ]
        )
            ->render('client.pro.transaction.index', compact('sub', 'todaySales', 'totalSales', 'monthSales'));
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
        $staff = Staff::where('id', $work_staff_id)
            ->where('cl_id', auth('client')->user()->id)
            ->first();
        if (!$staff) {
            return back()->withErrors('Staff Not found');
        }
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        if ($request->start_date) {
            $start_date = Carbon::parse($request->start_date)->format('Y-m-d H:i:s');
        }

        if ($request->end_date) {
            $end_date = Carbon::parse($request->end_date)->format('Y-m-d H:i:s');
        }

        return $dataTable->with(
            [
                'cl_id' => auth('client')->user()->id,
                'work_staff_id' => $staff->unique_id,
                'start_date' => $start_date,
                'end_date' => $end_date,
            ]
        )->render('client.pro.transaction.index', compact('staff'));
    }
}
