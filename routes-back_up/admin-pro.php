<?php

use Illuminate\Support\Facades\Route;

Route::middleware('admin')->group(function () {
    Route::get('/', 'ProSubscriptionController@index');
    Route::resource('proplans', 'PlanController');
    Route::get('prosubscriptions/download/{filename}', 'ProSubscriptionController@fileDownload');
    Route::resource('prosubscriptions', 'ProSubscriptionController');

    Route::post('staff/delete/all', 'StaffController@deleteAll');
    Route::resource('staff', 'StaffController');

    Route::resource('transactions', 'TransactionController');

    Route::resource('visitors', 'VisitorController');
});