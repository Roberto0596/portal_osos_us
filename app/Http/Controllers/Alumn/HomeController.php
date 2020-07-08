<?php

namespace App\Http\Controllers\Alumn;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Alumns\User;
use Input;
use Auth;

class HomeController extends Controller
{
	public function index()
	{
        $user = User::find(Auth::guard("alumn")->user()->id);
		$status = $user->inscripcion < 4? false:true;
        return view('Alumn.home.index')->with(["status"=>$status]);
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

    public function save(Request $request, Categories $categorie) 
    {
    }
}