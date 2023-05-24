<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    $currlist = DB::select('select * from zchk_app.binance where date = ?', [date("d.m.Y")]);
    $currlistold = DB::select('select * from zchk_app.binance where date = ?', [date("d.m.Y", strtotime("yesterday"))]);
    
    return view('p2p-app.index', ['currlist' => $currlist, 'currlistold' => $currlistold] );
});

// ВХОД-РЕГИСТРАЦИЯ-КОНСОЛЬ
Route::name('user.')->group(function () {
    
    Route::get('/console', function () {
        $currlist = DB::select('select * from zchk_app.binance where date = ?', [date("d.m.Y")]);
        $currlistold = DB::select('select * from zchk_app.binance where date = ?', [date("d.m.Y", strtotime("yesterday"))]);
        $pricelistt = DB::select('select * from zchk_app.price_book where user_id = ?', [Auth::user()->id]);
        return view('console', ['currlist' => $currlist, 'currlistold' => $currlistold, 'pricelistt' => $pricelistt] );
    })->middleware('auth')->name('console');
   
    Route::post('/addpriceform', function(){
        DB::insert('insert into zchk_app.price_book (user_id, date, price, value) values (?, ?, ?, ?)', [request()->user_id, str_replace("-", ".", request()->datet), request()->pricet, request()->valuet]);
        return back()->with('othert', 'Данные успешно добавлены');
    })->name('addprice');
    
    Route::post('/changepassword', [\App\Http\Controllers\RegisterController::class, 'changePassword'])->name('changepassword');
    //Route::view('/console', 'console')->middleware('auth')->name('console');
    
    Route::post('/deleteprice/{id}', function(){
        DB::table('zchk_app.price_book')->where('id', request()->deleteprice)->delete();
        //DB::delete('delete from zchk_app.price_book WHERE id = 8');
        return back()->with('tabledel', 'Данные успешно удалены');
    })->name('deleteprice');
    
    Route::get('/login', function () {
        if(Auth::check()) {return redirect(route('user.console'));}
        return view('login');
    })->name('login');
    
    Route::get('/register', function () {
        if(Auth::check()) {return redirect(route('user.console'));}
        return view('register');
    })->name('register');
    
    Route::post('/register', [\App\Http\Controllers\RegisterController::class, 'save']);
    Route::post('/login', [\App\Http\Controllers\LoginController::class, 'login']);
    
    Route::get('/logout', function(){
        Auth::logout();
        return redirect('/');
    })->name('logout');
});

//Auth::routes();

Route::get('/faq', function () {
    $currlist = DB::select('select * from zchk_app.binance where date = ?', [date("d.m.Y")]);
    $currlistold = DB::select('select * from zchk_app.binance where date = ?', [date("d.m.Y", strtotime("yesterday"))]);
    $currusdt = DB::select('select * from zchk_app.binance where fiat = ?', ['rub']);
    
    return view('p2p-app.faq', ['currlist' => $currlist, 'currlistold' => $currlistold, 'currusdt' => $currusdt] );
});

Route::get('/stat', function () {
    $currlist = DB::select('select * from zchk_app.binance where date = ?', [date("d.m.Y")]);
    $currlistold = DB::select('select * from zchk_app.binance where date = ?', [date("d.m.Y", strtotime("yesterday"))]);
    $currusdt = DB::select('select * from zchk_app.binance where fiat = ?', ['rub']);
    $portfoliouser = array();
    if(Auth::check()) {
        $portfoliouser = DB::select('select * from zchk_app.price_book where user_id = ?', [Auth::user()->id]);
    }
    return view('p2p-app.stat', ['currlist' => $currlist, 'currlistold' => $currlistold, 'currusdt' => $currusdt, 'portfoliouser' => $portfoliouser] );
});


