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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('/stocks')->name('stocks.')->group(function () {
    Route::get('/', 'StocksController@index')->name('all');
    Route::prefix('{stock}')->group(function () {
        Route::get('/', 'StocksController@get')->name('stock');
    });
});

Route::prefix('profile')->name('profile.')->group(function () {
    Route::get('alpha-vantage', 'HomeController@index')->name('alpha-vantage');
});

Auth::routes();
