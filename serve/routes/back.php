<?php

//登录
Route::get('/login', 'Controller@index');

//首页
Route::get('/dashboard', 'Controller@index');

//订单管理
Route::group(['prefix' => 'order'], function(){
    Route::get('/list', 'Controller@index');
    Route::get('/detail', 'Controller@index');
});

//商品管理
Route::group(['prefix' => 'product'], function(){
    Route::get('/list', 'Controller@index');
    Route::get('/edit', 'Controller@index');
    Route::get('/add', 'Controller@index');
    Route::get('/category', 'Controller@index');
});

//顾客管理
Route::group(['prefix' => 'customer'], function(){
    Route::get('/list', 'Controller@index');
});

//主题管理
Route::group(['prefix' => 'theme'], function(){
    Route::get('/list', 'Controller@index');
    Route::get('/setting', 'Controller@index');
});

//图片管理
Route::group(['prefix' => 'image'], function(){
    Route::get('/list', 'Controller@index');
});

//网站设置
Route::group(['prefix' => 'settings'], function(){
    Route::get('/basic', 'Controller@index');
    Route::get('/message', 'Controller@index');
    Route::get('/shipment', 'Controller@index');
    Route::get('/payment', 'Controller@index');
});

//用户
Route::group(['prefix' => 'user'], function(){
    Route::get('/edit', 'Controller@index');
});
