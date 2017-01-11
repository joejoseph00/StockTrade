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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});
Route::get('/embed/watchlist', function () {
    return view('embed/watchlist');
});

// API



Route::group(['prefix' => 'api/v1'], function () {

    Route::get('/stock/search', 'StockController@search');
    Route::post('/stock/buy', 'TransactionController@store');
    Route::get('/stock/data/{symbol}/{options?}', 'StockController@show');

    Route::get('/user/watchlist/add/{symbol}', 'UserController@addToWatchlist');
    Route::get('/user/watchlist/remove/{symbol}', 'UserController@removeFromWatchlist');
    Route::post('/user/isUsernameAvailable', 'UserController@isUsernameAvailable');
    Route::post('/user/create', 'UserController@store');
    Route::post('/user/authenticate', 'UserController@authenticate');
    Route::post('/user/isLoggedIn', 'UserController@isLoggedIn');
    Route::post('/user/logout', 'UserController@logout');
    Route::get('/user/watchlist', 'UserController@getWatchlist');
});
