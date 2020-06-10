<?php

//首页
Route::get('/', 'Controller@index');

//登录注册
Route::get('/login', 'Controller@index');
Route::get('/register', 'Controller@index');
Route::get('/forget', 'Controller@index');

//商品搜索
Route::get('/search', 'Controller@index');

//商品列表
Route::group(['prefix' => 'product'], function(){
    Route::get('/', 'Controller@index');
    Route::get('/{product}', 'Controller@index');
});

//商品分类
Route::group(['prefix' => 'types'], function(){
    Route::get('/', 'Controller@index');
});

//购物车
Route::get('/cart', 'Controller@index');

//结算
Route::group(['prefix' => 'checkout'], function(){
    Route::get('/create', 'Controller@index');
    Route::get('/pay', 'Controller@index');
    Route::get('/succeed', 'Controller@index');
});

//用户
Route::group(['prefix' => 'user'], function(){
    Route::get('/home', 'Controller@index');
    Route::get('/address', 'Controller@index');
    Route::get('/wallet', 'Controller@index');
    Route::group(['prefix' => 'order'], function(){
        Route::get('/', 'Controller@index');
        Route::get('/detail', 'Controller@index');
    });
});
