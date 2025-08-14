<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(){
        return view('auth.login');
    }
    public function authenticate(Request $request){
        $credentials = $request->validate([
            'email' => 'required|string|email|max:100',
            'password' => 'required|string|min:8'
        ]);

        //Attempt to authenticate
        if(Auth::attempt($credentials)){
            //regenerate session to prevent fixation attacks
            $request->session()->regenerate();
            return redirect(route('home'))->with('success',"Welcome User");
        }

        return back()->withErrors([
            'email'=> "The provided credentials are incorrect"
        ])->onlyInput('email');
    }
    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
