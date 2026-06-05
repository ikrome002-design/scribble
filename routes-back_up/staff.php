<?php

use App\Http\Controllers\OtpController;
use Illuminate\Support\Facades\Route;

Route::match(['get', 'post'], '/', 'AuthController@login');
Route::match(['get', 'post'], '/change-password', 'AuthController@changePassword');

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
    Route::Resource('staff', 'StaffController');

    Route::get('/transactions/per/business', 'TransactionController@businesses');
    Route::get('/transactions/per/business/{id}', 'TransactionController@transactionsPerBusiness');
    Route::Resource('transactions', 'TransactionController');

    Route::get('/visitors/per/business', 'VisitorController@businesses');
    Route::get('/visitors/per/business/{id}', 'VisitorController@VisitorsPerBusiness');
    Route::Resource('visitors', 'VisitorController');
});