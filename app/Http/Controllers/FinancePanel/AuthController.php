<?php

namespace App\Http\Controllers\FinancePanel;

use Auth;
use Input;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminUsers\AdminUser;

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
        $user = AdminUser::where('email', "=" ,$email)->first();
        
        if (!$user) {
            session()->flash('messages', 'error|No Existe un usuario con ese correo');
            return redirect()->back()->withInput();
        }

        if ($user->area_id == 2 || $user->area_id == 4) {       

            if (Auth::guard('finance')->attempt(['email' => $email, 'password' => $pass],$request->get('remember-me', 0)))
            {
                return redirect()->route('finance.home');
            }

            session()->flash('messages', 'error|El password es incorrecto');
            return redirect()->back()->withInput();
            
        } else {
            
            if ($user->area_id == 1) {
                return redirect()->route("computo.login")->withInput();
            } else {
                return redirect()->route("library.login")->withInput();
            }
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('finance')->logout();
        session()->flush();
        return redirect('/finance');
    }
}