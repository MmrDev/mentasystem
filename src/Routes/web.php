<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('wallet')->group(function () {
    Route::get('/index', 'WalletController@index')->name("wallet.index");
    Route::get('/show/{id}', 'WalletController@show')->name("wallet.show");
    Route::get('/create', 'WalletController@create')->name("wallet.create");
    Route::post('/store', 'WalletController@store')->name("wallet.store");
    Route::get('/edit/{id}', 'WalletController@edit')->name("wallet.edit");
    Route::post('/update/{id}', 'WalletController@update')->name("wallet.update");
    Route::post('/delete/{id}', 'WalletController@destroy')->name("wallet.delete");
});
Route::prefix('wallet')->group(function () {
    Route::get('/index', 'GoodsController@index')->name("goods.index");
    Route::get('/show/{id}', 'GoodsController@show')->name("goods.show");
    Route::get('/create', 'GoodsController@create')->name("goods.create");
    Route::post('/store', 'GoodsController@store')->name("goods.store");
    Route::get('/edit/{id}', 'GoodsController@edit')->name("goods.edit");
    Route::post('/update/{id}', 'GoodsController@update')->name("goods.update");
    Route::post('/delete/{id}', 'GoodsController@destroy')->name("goods.delete");
});
