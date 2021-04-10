<?php

use Illuminate\Support\Facades\Route;

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
Route::group(['middleware' => 'guest'], function () {
    Route::get('/', 'App\Http\Controllers\Auth\AuthController@login')->name('login');
    Route::post('/login', 'App\Http\Controllers\Auth\AuthController@authenticate');

    Route::get('/register', 'App\Http\Controllers\Auth\AuthController@register')->name('register');
    Route::post('/register', 'App\Http\Controllers\Auth\AuthController@storeUser');

    Route::get('auth/google', [App\Http\Controllers\Auth\GoogleSocialiteController::class, 'redirectToGoogle']);
    Route::get('callback/google', [App\Http\Controllers\Auth\GoogleSocialiteController::class, 'handleCallback']);

});

Route::group(['middleware' => 'auth'],function(){
    Route::get('/logout', 'App\Http\Controllers\Auth\AuthController@logout')->name('logout');
    Route::get('/dashboard', 'App\Http\Controllers\ProductController@index')->name('home');

    Route::group(['prefix' => 'categories'], function(){
        Route::get('/','App\Http\Controllers\CategoryController@index');
        Route::get('/list','App\Http\Controllers\CategoryController@list');
        Route::get('/{id}','App\Http\Controllers\CategoryController@show');
        Route::post('/','App\Http\Controllers\CategoryController@store');
        Route::post('/update','App\Http\Controllers\CategoryController@update');
        Route::delete('/{id}','App\Http\Controllers\CategoryController@destroy');
        Route::get('/sub-categories/{id}','App\Http\Controllers\CategoryController@sub_categories');
    });

    Route::group(['prefix' => 'products'], function(){
        Route::get('/add','App\Http\Controllers\ProductController@create');
        Route::post('/','App\Http\Controllers\ProductController@store');
        Route::get('/{id}','App\Http\Controllers\ProductController@show');
        Route::post('/update','App\Http\Controllers\ProductController@update');
        Route::delete('/{id}','App\Http\Controllers\ProductController@destroy');
    });

    Route::get('/resume','App\Http\Controllers\ResumeController@index');
    Route::post('/resume','App\Http\Controllers\ResumeController@store');


    Route::get('/countries','App\Http\Controllers\MasterController@countries');
    Route::get('/states/{id}','App\Http\Controllers\MasterController@states');
    Route::get('/cities/{id}','App\Http\Controllers\MasterController@cities');
});