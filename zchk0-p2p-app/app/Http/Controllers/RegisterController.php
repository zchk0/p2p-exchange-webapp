<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function save (Request $request){
        if (Auth::check()){ 
            return redirect(route('user.console'));
        }
        
        $validateFields = $request->validate([
            'email' => 'required|email',
            'login' => 'required|alpha_dash:ascii|lowercase|size:3',
            //'name' => 'required',
            'password' => 'required|size:3',
            'password2' => 'required|size:3'
        ]);
        
        $validateFields['name'] = "none";
        
        if(User::where('email', $validateFields ['email'])->exists() || User::where('login', $validateFields ['login'])->exists()) { 
            return redirect(route('user.register'))->withErrors([
            'other' => 'Пользователь с такими логином/почтой уже зарегистрирован'
            ]);
        }
        
        if($validateFields['password'] != $validateFields ['password2']) { 
            return redirect(route('user.register'))->withErrors([
            'other' => 'Пароли не совпадают'
            ]);
        }
    
        //dd($validateFields);
    
        $user = User::create($validateFields);
        if($user){
            Auth::login($user);
            return redirect(route('user.console'));
        }
    
        return redirect(route('user.register'))->withErrors([
            'formError' => 'Произошла ошибка при сохранении пользователя'
        ]);
    }
    
    public function changePassword(Request $request) {
        $request->validate([
            'old_password' => 'required',
            'password' => 'required',
        ]);

        $user = Auth::user();
        $hashedPassword = $user->password;

        if (Hash::check($request->old_password, $hashedPassword)) {
            $user->fill([
            'password' => $request->password
        ])->save();
    
        return redirect()->back()->with('textt', 'Ваш пароль успешно изменен.');    
    }

    return redirect()->back()->with('error', 'Старый пароль не совпадает.');
}

}
