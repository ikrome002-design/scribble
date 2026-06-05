<?php

use Illuminate\Support\Facades\Route;

Route::middleware('admin')->group(function () {
    Route::get('/', 'ProSubscriptionController@index');
    Route::resource('proplans', 'PlanController');
    Route::get('prosubscriptions/download/{filename}', 'ProSubscriptionController@fileDownload');
    Route::resource('prosubscriptions', 'ProSubscriptionController')->names([
        'index' => 'admin.prosubscriptions.index',
        'create' => 'admin.prosubscriptions.create',
        'store' => 'admin.prosubscriptions.store',
        'show' => 'admin.prosubscriptions.show',
        'edit' => 'admin.prosubscriptions.edit',
        'update' => 'admin.prosubscriptions.update',
        'destroy' => 'admin.prosubscriptions.destroy',
    ]);

    Route::post('staff/delete/all', 'StaffController@deleteAll');

    Route::resource('staff', 'StaffController')->names([
        'index' => 'admin.staff.index',
        'create' => 'admin.staff.create',
        'store' => 'admin.staff.store',
        'show' => 'admin.staff.show',
        'edit' => 'admin.staff.edit',
        'update' => 'admin.staff.update',
        'destroy' => 'admin.staff.destroy',
    ]);

    Route::resource('transactions', 'TransactionController')->names([
        'index' => 'admin.transactions.index',
        'create' => 'admin.transactions.create',
        'store' => 'admin.transactions.store',
        'show' => 'admin.transactions.show',
        'edit' => 'admin.transactions.edit',
        'update' => 'admin.transactions.update',
        'destroy' => 'admin.transactions.destroy',
    ]);

    Route::post('visitors/checkout/{id}', 'VisitorController@checkOutVisitor');
    Route::resource('visitors', 'VisitorController')->names([
        'index' => 'admin.visitors.index',
        'create' => 'admin.visitors.create',
        'store' => 'admin.visitors.store',
        'show' => 'admin.visitors.show',
        'edit' => 'admin.visitors.edit',
        'update' => 'admin.visitors.update',
        'destroy' => 'admin.visitors.destroy',
    ]);
});