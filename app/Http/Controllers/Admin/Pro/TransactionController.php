<?php

namespace App\Http\Controllers\Admin\Pro;

use App\Http\Controllers\Controller;
use App\Models\ShortcodeTransaction;
use Illuminate\Http\Request;
use App\DataTables\ShortcodeTransactionDataTable;
use App\Models\ProSubscription;
use  Illuminate\Support\Carbon;

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

        return $dataTable->with(
            [
                'start_date' => $start_date,
                'end_date' => $end_date,
            ]
        )
            ->render('admin.pro.transaction.index');
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
}