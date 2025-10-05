<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StatController;

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

// СТРАНИЦА СТАТИСТИКИ
Route::get('/stat', [StatController::class, 'index'])->name('stat.index');
