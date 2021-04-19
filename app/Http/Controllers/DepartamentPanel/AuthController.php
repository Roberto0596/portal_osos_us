<?php

namespace App\Http\Controllers\DepartamentPanel;

use Auth;
use Input;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminUsers\AdminUser;

class AuthController extends Controller
{
    public function login() 
    {   
        if (Auth::guard("departament")->check())
        {
            return redirect()->route('departament.home');
        }  
        return view('DepartamentPanel.Auth.login');
    }

    public function postLogin(Request $request)
    {
        $email = $request->input('email');
        $pass = $request->input('password');

        $user = AdminUser::where('email', $email)
                ->where("is_departament", 1)
                ->orWhere("is_superuser", 1)
                ->first();

        if (!$user) {
            session()->flash('messages', 'error|No Existe un usuario con ese correo');
            return redirect()->back()->withInput();
        }

        if (Auth::guard('departament')->attempt(['email' => $email, 'password' => $pass],$request->get('remember-me', 0))) {
            return redirect()->route('departament.home');
        }

        session()->flash('messages', 'error|El password es incorrecto');
        return redirect()->back()->withInput();
    }

    public function logout(Request $request)
    {
        Auth::guard('departament')->logout();
        session()->flush();
        return redirect('/departaments');
    }
}