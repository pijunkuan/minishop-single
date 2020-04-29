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



Route::any('version',function() {
    echo "mini-shop v~1.0";
});

Route::prefix('customer')->namespace('Customer')->group(function(){
    Route::post('register','CustomerController@register');
    Route::post('login',"CustomerController@login");
    Route::post('refresh','CustomerController@refresh');
    Route::get('me','CustomerController@me');
    Route::get('logout','CustomerController@logout');
    Route::put('edit','CustomerController@update');
});

Route::prefix('admin')->namespace('Admin')->group(function(){
   Route::post('login',"AdminController@login");
   Route::post('refresh',"AdminController@refresh");
   Route::get('me',"AdminController@me");
   Route::get('logout',"AdminController@logout");
   Route::put('edit',"AdminController@update");
});

Route::middleware('auth:customers')->group(function(){
    Route::prefix('wallet')->namespace('Wallet')->group(function(){
        Route::get('','WalletController@index');
        Route::get('balance','WalletController@balance');
    });
    Route::prefix('cart')->namespace('Cart')->group(function(){
        Route::get('','CartController@index');
        Route::post('','CartController@store');
        Route::put('','CartController@update');
        Route::delete('{variant_id}','CartController@destroy');
    });
});

Route::middleware('auth:admins')->prefix("admin")->group(function(){
    Route::prefix('product')->namespace('Product')->group(function(){
       Route::post('',"ProductController@store");
       Route::put('{product}',"ProductController@update");
       Route::delete('{product}',"ProductController@destroy");
       Route::get('{product}',"ProductController@admin_show");
       Route::get('',"ProductController@admin_index");
    });
});



