<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['team', 'clientMainSubscription'])->group(function () {
    Route::resource('proplans', 'PlanController')->names([
        'index' => 'team.user.proplans.index',
        'create' => 'team.user.proplans.create',
        'store' => 'team.user.proplans.store',
        'show' => 'team.user.proplans.show',
        'edit' => 'team.user.proplans.edit',
        'update' => 'team.user.proplans.update',
        'destroy' => 'team.user.proplans.destroy',
    ]);
    Route::get('mpesa/integration/guide', 'ProSubscriptionController@mpesaIntegrationGuide');
    Route::get('integration/incomplete', 'ProSubscriptionController@integrationIncomplete');
    Route::get('/', 'ProSubscriptionController@index');
    Route::match(['get', 'post'], '/prosubscriptions/subscribe', 'ProSubscriptionController@subscribe');
    Route::post('/prosubscriptions/opt/in/{id}', 'ProSubscriptionController@optIn');
    Route::post('/prosubscriptions/opt/out/{id}', 'ProSubscriptionController@optOut');
    Route::get('/prosubscriptions/generate/invoice/{id}', 'ProSubscriptionController@generateInvoice');

    Route::Resource('prosubscriptions', 'ProSubscriptionController')->names([
        'index' => 'team.user.prosubscriptions.index',
        'create' => 'team.user.prosubscriptions.create',
        'store' => 'team.user.prosubscriptions.store',
        'show' => 'team.user.prosubscriptions.show',
        'edit' => 'team.user.prosubscriptions.edit',
        'update' => 'team.user.prosubscriptions.update',
        'destroy' => 'team.user.prosubscriptions.destroy',
    ]);


    Route::get('staff/assign/roles', 'AssignRoleController@index');
    Route::get('staff/work/history', 'StaffController@workHistory');
    Route::match(['get', 'post'], 'staff/staff/roles/{id}', 'AssignRoleController@staffRoles');
    Route::match(['get', 'post'], 'staff/transactions/roles/{id}', 'AssignRoleController@transactionsRoles');
    Route::match(['get', 'post'], 'staff/visitors/roles/{id}', 'AssignRoleController@visitorsRoles');

    Route::post('staff/delete/all', 'StaffControlle@deleteAll');
    Route::resource('staff', 'StaffController')->names([
        'index' => 'team.user.staff.index',
        'create' => 'team.user.staff.create',
        'store' => 'team.user.staff.store',
        'show' => 'team.user.staff.show',
        'edit' => 'team.user.staff.edit',
        'update' => 'team.user.staff.update',
        'destroy' => 'team.user.staff.destroy',
    ]);

    Route::get('staff/work/history/transactions/{id}', 'TransactionController@workHistory');
    Route::get('/transactions/per/business', 'TransactionController@businesses');
    Route::get('/transactions/per/business/{id}', 'TransactionController@transactionsPerBusiness');
    Route::resource('transactions', 'TransactionController')->names([
        'index' => 'team.user.transactions.index',
        'create' => 'team.user.transactions.create',
        'store' => 'team.user.transactions.store',
        'show' => 'team.user.transactions.show',
        'edit' => 'team.user.transactions.edit',
        'update' => 'team.user.transactions.update',
        'destroy' => 'team.user.transactions.destroy',
    ]);

    Route::get('staff/work/history/visitors/{id}', 'VisitorController@workHistory');
    Route::post('visitors/checkout/{id}', 'VisitorController@checkOutVisitor');
    Route::get('/visitors/per/business', 'VisitorController@businesses');
    Route::get('/visitors/per/business/{id}', 'VisitorController@visitorsPerBusiness');
    Route::get('visitors/per/business/minor/{id}', 'VisitorController@vistorsPerBusinessMinor');
    Route::resource('/visitors', 'VisitorController')->names([
        'index' => 'team.user.visitors.index',
        'create' => 'team.user.visitors.create',
        'store' => 'team.user.visitors.store',
        'show' => 'team.user.visitors.show',
        'edit' => 'team.user.visitors.edit',
        'update' => 'team.user.visitors.update',
        'destroy' => 'team.user.visitors.destroy',
    ]);
    Route::get('/visitor/business/autofill', 'VisitorBusinessController@businessAutofill');
    Route::resource('/visitorBusiness', 'VisitorBusinessController')->names([
        'index' => 'team.user.visitorBusiness.index',
        'create' => 'team.user.visitorBusiness.create',
        'store' => 'team.user.visitorBusiness.store',
        'show' => 'team.user.visitorBusiness.show',
        'edit' => 'team.user.visitorBusiness.edit',
        'update' => 'team.user.visitorBusiness.update',
        'destroy' => 'team.user.visitorBusiness.destroy',
    ]);
});
