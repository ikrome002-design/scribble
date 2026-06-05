<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//mpesa callback 
Route::post('/client/transactions/daraja/validation', 'MpesaResponseController@validation');
Route::post('/client/transactions/daraja/confirmation', 'MpesaResponseController@confirmation');
