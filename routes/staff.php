<?php

use App\Http\Controllers\OtpController;
use Illuminate\Support\Facades\Route;

Route::match(['get', 'post'], '/', 'AuthController@login');
Route::match(['get', 'post'], '/change-password', 'AuthController@changePassword');
Route::post('/forgot-password', 'AuthController@forgotPassword');

Route::middleware('staff')->group(function () {

    Route::get('/profile', 'ProfileController@index');
    Route::get('/business', 'TransactionController@subscriptions');
    Route::get('/prosubscriptions', 'TransactionController@subscriptions');
    Route::get('/prosubscriptions/{id}', 'TransactionController@transactions');
    Route::get('/logout', 'AuthController@logout');

    Route::get('staff/assign/roles', 'AssignRoleController@index');
    Route::match(['get', 'post'], 'staff/staff/roles/{id}', 'AssignRoleController@staffRoles');
    Route::match(['get', 'post'], 'staff/transactions/roles/{id}', 'AssignRoleController@transactionsRoles');
    Route::match(['get', 'post'], 'staff/visitors/roles/{id}', 'AssignRoleController@visitorsRoles');
    Route::get('staff/work/history', 'StaffController@workHistory');
    Route::resource('staff', 'StaffController')->names([
        'index' => 'staff.staff.index',
        'create' => 'staff.staff.create',
        'store' => 'staff.staff.store',
        'show' => 'staff.staff.show',
        'edit' => 'staff.staff.edit',
        'update' => 'staff.staff.update',
        'destroy' => 'staff.staff.destroy',
    ]);

    Route::get('staff/work/history/transactions/{id}', 'TransactionController@workHistory');
    Route::get('/transactions/per/business', 'TransactionController@businesses');
    Route::get('/transactions/per/business/{id}', 'TransactionController@transactionsPerBusiness');
    Route::Resource('transactions', 'TransactionController')->names([
        'index' => 'staff.transactions.index',
        'create' => 'staff.transactions.create',
        'store' => 'staff.transactions.store',
        'show' => 'staff.transactions.show',
        'edit' => 'staff.transactions.edit',
        'update' => 'staff.transactions.update',
        'destroy' => 'staff.transactions.destroy',
    ]);

    Route::get('staff/work/history/visitors/{id}', 'VisitorController@workHistory');
    Route::get('/visitors/per/business', 'VisitorController@businesses');
    Route::get('/visitors/per/business/{id}', 'VisitorController@VisitorsPerBusiness');
    Route::post('visitors/checkout/{id}', 'VisitorController@checkOutVisitor');
    Route::get('visitors/per/business/minor/{id}', 'VisitorController@vistorsPerBusinessMinor');
    Route::Resource('visitors', 'VisitorController')->names([
        'index' => 'staff.visitors.index',
        'create' => 'staff.visitors.create',
        'store' => 'staff.visitors.store',
        'show' => 'staff.visitors.show',
        'edit' => 'staff.visitors.edit',
        'update' => 'staff.visitors.update',
        'destroy' => 'staff.visitors.destroy',
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
