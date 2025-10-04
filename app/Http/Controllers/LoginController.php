<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login (Request $request){
        if (Auth::check()){
            return redirect()->intended (route('user.console'));
        }
        
        if(Auth::attempt(['email' => $request->elog, 'password' => $request->password])){
            return redirect()->intended (route('user.console'));
        }
        
        if(Auth::attempt(['login' => $request->elog, 'password' => $request->password])){
            return redirect()->intended (route('user.console'));
        }
        
        return redirect(route('user.login'))->withErrors([
            'other' => 'Не удалось авторизоваться'
        ]);
    }
}
