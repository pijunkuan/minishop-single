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

});

