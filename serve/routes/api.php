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


Route::any('version', function (Request $request) {
    echo "mini-shop v~1.0";
    $resault = dns_get_record("pinuocaostudio.com",DNS_CNAME);
    var_dump($resault);
    var_dump($request->getRequestUri());
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
    Route::get('me', "AdminController@me");
    Route::put('me', "AdminController@update");
});
Route::apiResource('product', 'Product\ProductController')->only(['index', 'show']);
Route::get('category', 'Category\CategoryController@index');
Route::get('theme', "Theme\ThemeController@get");
Route::get('template', "Template\TemplateController@get");

Route::prefix('pay/{no}')->namespace('Pay')->group(function () {
    Route::get('wallet', "PayController@wallet")->middleware('auth:customers');
});

Route::middleware('auth:customers')->group(function () {
    Route::prefix('wallet')->namespace('Wallet')->group(function () {
        Route::get('', 'WalletController@index');
        Route::get('balance', 'WalletController@balance');
    });
    Route::prefix('cart')->namespace('Cart')->group(function () {
        Route::post('cache', 'CartController@cache_in');
        Route::get('cache/{key}', 'CartController@cache_out');
        Route::get('', 'CartController@index');
        Route::post('', 'CartController@store');
        Route::put('', 'CartController@update');
        Route::delete('{variant_id}', 'CartController@destroy');
    });
    Route::apiResource('address', 'Address\AddressController')->except(['show']);
    Route::post('order/calc', 'Order\OrderController@calc');
    Route::post('order/{order}/pay/{payment}', "Order\OrderController@pay_create")->name('order.pay');
    Route::apiResource('order', "Order\OrderController")->except(['destroy']);
});

Route::middleware('auth:admins')->prefix("admin")->group(function () {
    Route::get('dashboard/{type}',"Dashboard\AdminDashboardController@dashboard_static");
    Route::apiResource('product', 'Product\AdminProductController');
    Route::apiResource('image', "Image\ImageController")->only(['store', 'destroy', 'index']);
    Route::prefix('order')->namespace('Order')->group(function () {
        Route::get('', 'AdminOrderController@index');
        Route::put('status/{order}', "AdminOrderController@status");
        Route::put('{order}',"AdminOrderController@update");
        Route::get('{order}/shipment', 'AdminOrderShipmentController@index');
        Route::post('{order}/shipment', 'AdminOrderShipmentController@store');
        Route::get('{order}', "AdminOrderController@show");
    });
    Route::prefix('customer')->namespace('Customer')->group(function () {
        Route::put('{customer}', 'AdminCustomerController@update');
        Route::get('', 'AdminCustomerController@index');
    });
    Route::prefix('wallet/{customer}')->namespace('Wallet')->group(function () {
        Route::get('balance', 'AdminWalletController@balance');
        Route::get('', 'AdminWalletController@index');
        Route::post('', 'AdminWalletController@store');
    });
    Route::apiResource('category', 'Category\AdminCategoryController')->except(['show']);
    Route::apiResource('shipment', 'Shipment\AdminShipmentController');
    Route::get('theme', "Theme\ThemeController@get");
    Route::post('theme', "Theme\ThemeController@put");
    Route::get('template', "Template\TemplateController@get");
    Route::post('template', "Template\TemplateController@put");

});



