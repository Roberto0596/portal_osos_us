<?php

namespace App\Http\Controllers\AdminPanel;

use Auth;
use Input;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminUsers\AdminUser;

class AuthController extends Controller
{
    public function login() 
    {   
        if (Auth::guard("admin")->check())
        {
            return redirect()->route('admin.home');
        }  
        return view('AdminPanel.auth.login');
    }

    public function postLogin(Request $request)
    {
        $email = $request->input('email');
        $pass = $request->input('password');

        $user = AdminUser::where([['email', "=" ,$email],['area_id',"=","4"]])->first();

        if (!$user) {
            session()->flash('messages', 'error|No Existe un usuario con ese correo');
            return redirect()->back()->withInput();
        }

        if (Auth::guard('admin')->attempt(['email' => $email, 'password' => $pass],$request->get('remember-me', 0)))
        {
            return redirect()->route('admin.home');
        }
        session()->flash('messages', 'error|El password es incorrecto');
        return redirect()->back()->withInput();
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        session()->flush();
        return redirect()->route('admin.login');
    }   
}