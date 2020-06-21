<?php

namespace App\Http\Controllers\FinancePanel;

use Auth;
use Input;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Finance\FinanceUser;

class AuthController extends Controller
{
    public function login() 
    {   
        if (Auth::guard("finance")->check())
        {
            return redirect()->route('finance.home');
        }  
        return view('FinancePanel.Auth.login');
    }

    public function postLogin(Request $request)
    {
        $email = $request->input('email');
        $pass = $request->input('password');

        $user = FinanceUser::where('email', "=" ,$email)->first();

        if (!$user) {
            session()->flash('messages', 'error|No Existe un usuario con ese correo');
            return redirect()->back();
        }

        if (Auth::guard('finance')->attempt(['email' => $email, 'password' => $pass],$request->get('remember-me', 0)))
        {
            return redirect()->route('finance.home');
        }
        session()->flash('messages', 'error|El password es incorrecto');
        return redirect()->back();
    }

    public function logout(Request $request)
    {
        Auth::guard('finance')->logout();
        session()->flush();
        return redirect('/finance');
    }
}