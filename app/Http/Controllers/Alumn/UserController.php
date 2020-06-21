<?php

namespace App\Http\Controllers\Alumn;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Alumns\User;
use Input;
use Auth;

class UserController extends Controller
{
	public function index()
	{
        $current_id = Auth::guard('alumn')->user()->id;
        $current_user = User::find($current_id);
		return view('Alumn.user.index')->with(["user"=>$current_user]);
	}

	public function add() 
	{
    }

    public function edit($id)
    {
    }

    public function delete($id)
    {
    }

    public function registerAlumn(Request $request, User $user) 
    {
        //validar que un correo no exista.
        $validate = User::where("email","=", $request->input("email"))->get();

        if(!$validate->isEmpty())
        {
            return redirect()->route("home");
        }

        $user->name = $request->input("name");
        $user->lastname = $request->input("lastname");
        $user->email = $request->input("email");
        $user->password = bcrypt($request->input("password"));
        $user->save();

        if (Auth::guard("alumn")->check())
        {
            Auth::guard('alumn')->logout();
            session()->flush();
        } 

         $credentials = $request->only('email', 'password');

        if (Auth::guard('alumn')->attempt($credentials))
        {
            return redirect()->route('alumn.home');
        }
        else
        {
            User::destroy($user->id);
            session()->flash("message","error|No se completo el registro, favor de intentarlo de nuevo");
            return redirect()->route("home");
        }
    }
}