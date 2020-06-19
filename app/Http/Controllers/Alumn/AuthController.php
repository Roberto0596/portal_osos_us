<?php

namespace App\Http\Controllers\Alumn;

use Auth;
use Input;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login() 
    {
        if (Auth::check())
        {
            return redirect('alumn.home');
        }
        
        return view('Alumn.auth.login');
    }

    public function logout(Request $request)
    {
        // Auth::guard('orgs')->logout();
        // session()->flush();
        return redirect('/');
    }
}