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

$locale = request()->server("HTTP_ACCEPT_LANGUAGE") ?? "en";
App::setLocale($locale);

/*Route::group(["prefix" => "balance", "middleware" => "auth:api", "namespace" => "Api"], function () {
    Route::get('/', 'BalanceController@index');
    Route::get('/{id}', 'BalanceController@show');
    Route::post('````', 'BalanceController@store');
    Route::delete('/{id}', 'BalanceController@destroy');
});*/

//wallet
Route::group([
    'prefix' => 'wallet',
//    "middleware" => "auth:api",
    'namespace' => 'Api'
], function () {
    Route::post('/create', 'WalletController@create');
    Route::get('/list', 'WalletController@list');
    Route::get('/show/{id}', 'WalletController@show');
    Route::put('/update/{id}', 'WalletController@update');
    Route::delete('/delete/{id}', 'WalletController@destroy');
    Route::get('/wallet-credit', 'WalletController@walletCredit')->middleware('api:auth');
});

//transaction
Route::group([
    "middleware" => "auth:api",
    'namespace' => 'Api'
], function () {
    Route::resource("goods", "GoodsController");

});

//wallet route   "middleware" => "auth:api",
Route::group(['namespace' => 'Api'], function () {
    Route::resource("wallets", "WalletController");
});

//account type route "middleware" => "auth:api",
Route::group([
    'namespace' => 'Api'
], function () {
    Route::resource("accountType", "AccountTypeController");
});
Route::group(["prefix" => "account", 'namespace' => 'Api'], function () {
    Route::get('/types', 'AccountTypeController@index');
});

//create campaign routes
Route::resource('campaigns', 'Api\CampaignController')->middleware("auth:api");

//order routes
Route::group([
    "prefix" => "product",
    "middleware" => "auth:api",
    'namespace' => 'Api'
], function () {
    Route::resource("orders", "OrderController");
    Route::resource("orders/{mobile}", "OrderController@show");
    Route::post("orders/refund", "OrderController@refund");
});

/*---------------account module route---------------, "middleware" => ["auth:api", "WebPrevent"]-----*/

Route::group(['namespace' => 'Api'], function () {
    Route::resource('accounts', 'AccountController');
    Route::get('/{id}', 'AccountController@show');
    Route::post('/', 'AccountController@store');
    Route::delete('/{id}', 'AccountController@destroy');
});

Route::group(["middleware" => ["auth:api", "WebPrevent"], "namespace" => "Api"], function () {
    Route::resource("groups", "GroupController");
    Route::post("groups", "GroupController@");
});

Route::group(["middleware" => ["auth:api", "WebPrevent"], "namespace" => "Api"], function () {
    Route::resource("credits", "CreditController");
});
