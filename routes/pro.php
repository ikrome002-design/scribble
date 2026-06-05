<?php

use Illuminate\Support\Facades\Route;

Route::middleware('client')->group(function () {

    Route::get('mpesa/integration/guide', 'ProSubscriptionController@mpesaIntegrationGuide');
    Route::get('integration/incomplete', 'ProSubscriptionController@integrationIncomplete');
    Route::get('/', 'ProSubscriptionController@index');
    Route::match(['get', 'post'], '/prosubscriptions/subscribe', 'ProSubscriptionController@subscribe');
    Route::post('/prosubscriptions/opt/in/{id}', 'ProSubscriptionController@optIn');
    Route::get('/prosubscriptions/generate/invoice/{id}', 'ProSubscriptionController@generateInvoice');

    Route::Resource('prosubscriptions', 'ProSubscriptionController')->names([
        'index' => 'user.prosubscriptions.index',
        'create' => 'user.prosubscriptions.create',
        'store' => 'user.prosubscriptions.store',
        'show' => 'user.prosubscriptions.show',
        'edit' => 'user.prosubscriptions.edit',
        'update' => 'user.prosubscriptions.update',
        'destroy' => 'user.prosubscriptions.destroy',
    ]);


    Route::get('staff/assign/roles', 'AssignRoleController@index');
    Route::get('staff/work/history', 'StaffController@workHistory');
    Route::match(['get', 'post'], 'staff/staff/roles/{id}', 'AssignRoleController@staffRoles');
    Route::match(['get', 'post'], 'staff/transactions/roles/{id}', 'AssignRoleController@transactionsRoles');
    Route::match(['get', 'post'], 'staff/visitors/roles/{id}', 'AssignRoleController@visitorsRoles');

    Route::post('staff/delete/all', 'StaffControlle@deleteAll');
    Route::resource('staff', 'StaffController')->names([
        'index' => 'user.staff.index',
        'create' => 'user.staff.create',
        'store' => 'user.staff.store',
        'show' => 'user.staff.show',
        'edit' => 'user.staff.edit',
        'update' => 'user.staff.update',
        'destroy' => 'user.staff.destroy',
    ]);

    Route::get('staff/work/history/transactions/{id}', 'TransactionController@workHistory');
    Route::get('/transactions/per/business', 'TransactionController@businesses');
    Route::get('/transactions/per/business/{id}', 'TransactionController@transactionsPerBusiness');
    Route::resource('transactions', 'TransactionController')->names([
        'index' => 'user.transactions.index',
        'create' => 'user.transactions.create',
        'store' => 'user.transactions.store',
        'show' => 'user.transactions.show',
        'edit' => 'user.transactions.edit',
        'update' => 'user.transactions.update',
        'destroy' => 'user.transactions.destroy',
    ]);

    Route::get('staff/work/history/visitors/{id}', 'VisitorController@workHistory');
    Route::post('visitors/checkout/{id}', 'VisitorController@checkOutVisitor');
    Route::get('/visitors/per/business', 'VisitorController@businesses');
    Route::get('/visitors/per/business/{id}', 'VisitorController@visitorsPerBusiness');
    Route::get('visitors/per/business/minor/{id}', 'VisitorController@vistorsPerBusinessMinor');
    Route::resource('/visitors', 'VisitorController')->names([
        'index' => 'user.visitors.index',
        'create' => 'user.visitors.create',
        'store' => 'user.visitors.store',
        'show' => 'user.visitors.show',
        'edit' => 'user.visitors.edit',
        'update' => 'user.visitors.update',
        'destroy' => 'user.visitors.destroy',
    ]);
    Route::get('/visitor/business/autofill', 'VisitorBusinessController@businessAutofill');
    Route::resource('/visitorBusiness', 'VisitorBusinessController')->names([
        'index' => 'user.visitors.business.index',
        'create' => 'user.visitors.business.create',
        'store' => 'user.visitors.business.store',
        'show' => 'user.visitors.business.show',
        'edit' => 'user.visitors.business.edit',
        'update' => 'user.visitors.business.update',
        'destroy' => 'user.visitors.business.destroy',
    ]);
});
