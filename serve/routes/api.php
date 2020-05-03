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


Route::any('version', function () {
    echo "mini-shop v~1.0";
});

Route::prefix('customer')->namespace('Customer')->group(function () {
    Route::post('login', "CustomerController@login");
    Route::get('refresh', 'CustomerController@refresh');
    Route::get('logout', 'CustomerController@logout');
    Route::post('', 'CustomerController@register');
    Route::put('', 'CustomerController@update');
    Route::get('', 'CustomerController@me');
});

Route::prefix('admin')->namespace('Admin')->group(function () {
    Route::post('login', "AdminController@login");
    Route::post('refresh', "AdminController@refresh");
    Route::get('logout', "AdminController@logout");
    Route::get('', "AdminController@me");
    Route::put('', "AdminController@update");
});
Route::apiResource('product','Product\ProductController')->only(['index','show']);
Route::get('category','Category\CategoryController@index');

Route::prefix('pay/{no}')->namespace('Pay')->group(function(){
    Route::get('wallet',"PayController@wallet")->middleware('auth:customers');
});

Route::middleware('auth:customers')->group(function () {
    Route::prefix('wallet')->namespace('Wallet')->group(function () {
        Route::get('', 'WalletController@index');
        Route::get('balance', 'WalletController@balance');
    });
    Route::prefix('cart')->namespace('Cart')->group(function () {
        Route::get('', 'CartController@index');
        Route::post('', 'CartController@store');
        Route::put('', 'CartController@update');
        Route::delete('{variant_id}', 'CartController@destroy');
    });
    Route::apiResource('address','Address\AddressController')->except(['show']);
    Route::post('order/calc','Order\OrderController@calc');
    Route::post('order/{order}/pay/{payment}',"Order\OrderController@pay_create")->name('order.pay');
    Route::apiResource('order',"Order\OrderController")->except(['destroy']);
});

Route::middleware('auth:admins')->prefix("admin")->group(function () {
    Route::apiResource('product','Product\AdminProductController');
    Route::apiResource('image',"Image\ImageController")->only(['store','destroy','index']);
    Route::prefix('order')->namespace('Order')->group(function(){
        Route::get('','AdminOrderController@index');
        Route::get('{order}/shipment','AdminOrderShipmentController@index');
        Route::post('{order}/shipment','AdminOrderShipmentController@store');
        Route::put('{order}',"AdminOrderController@update");
        Route::get('{order}',"AdminOrderController@show");
    });
    Route::apiResource('category','Category\AdminCategoryController')->except(['show']);
    Route::apiResource('shipment','Shipment\AdminShipmentController');
});



