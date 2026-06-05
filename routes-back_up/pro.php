<?php

use Illuminate\Support\Facades\Route;

Route::middleware('client')->group(function () {

    Route::get('integration/incomplete', 'ProSubscriptionController@integrationIncomplete');
    Route::get('/', 'ProSubscriptionController@index');
    Route::match(['get', 'post'], '/prosubscriptions/subscribe', 'ProSubscriptionController@subscribe');
    Route::post('/prosubscriptions/opt/in/{id}', 'ProSubscriptionController@optIn');
    Route::get('/prosubscriptions/generate/invoice/{id}', 'ProSubscriptionController@generateInvoice');
    Route::Resource('prosubscriptions', 'ProSubscriptionController');


    Route::get('staff/assign/roles', 'AssignRoleController@index');
    Route::match(['get', 'post'], 'staff/staff/roles/{id}', 'AssignRoleController@staffRoles');
    Route::match(['get', 'post'], 'staff/transactions/roles/{id}', 'AssignRoleController@transactionsRoles');
    Route::match(['get', 'post'], 'staff/visitors/roles/{id}', 'AssignRoleController@visitorsRoles');

    Route::post('staff/delete/all', 'StaffControlle@deleteAll');
    Route::resource('staff', 'StaffController');

    Route::get('/transactions/per/business', 'TransactionController@businesses');
    Route::get('/transactions/per/business/{id}', 'TransactionController@transactionsPerBusiness');
    Route::resource('transactions', 'TransactionController');

    Route::post('visitors/checkout/{id}', 'VisitorController@checkOutVisitor');
    Route::get('/visitors/per/business', 'VisitorController@businesses');
    Route::get('/visitors/per/business/{id}', 'VisitorController@visitorsPerBusiness');
    Route::resource('/visitors', 'VisitorController');
});