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
Route::get('/user/watchlist', function (Request $request) {
    return response()
    ->json(['GOOGL','YHOO','NDAQ','FB']);
});
