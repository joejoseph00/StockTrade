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
    Route::get('/stock/data/{symbol}/{options?}', 'StockController@show');
    Route::get('/user/watchlist/add/{symbol}', 'UserController@addToWatchlist');
    Route::post('/user/isUsernameAvailable', 'UserController@isUsernameAvailable');
    Route::post('/user/create', 'UserController@store');
});
Route::get('/user/watchlist', function (Request $request) {
    return response()
    ->json([
        [
            'symbol' => 'GOOGL',
            'name' => 'Alphabet Inc.'
        ],
        [
            'symbol' => 'YHOO',
            'name' => 'Yahoo',
        ],
        [
            'symbol' => 'NDAQ',
            'name' => 'Nasdaq'
        ]
    ]);
});
