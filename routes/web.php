<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// ГЛАВНАЯ СТРАНИЦА
Route::get('/', function () {
    return view('p2p-app.index');
});

// СТРАНИЦА FAQ
Route::get('/faq', function () {
    return view('p2p-app.faq');
});
