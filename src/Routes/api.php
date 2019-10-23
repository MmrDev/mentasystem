<?php

use Illuminate\Http\Request;

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

//wallet
Route::group([
    'prefix' => 'wallet',
//    "middleware" => "auth:api",
    'namespace' => 'Mentasystem\Wallet\Http\Controllers\Api'
], function () {
    Route::resource('/', 'WalletController');
    Route::resource('account_types', 'AccountTypeController');
    Route::resource('accounts', 'AccountController');
    Route::post('accounts/charge', 'AccountController@charge');
    Route::resource('orders', 'OrderController');
});


