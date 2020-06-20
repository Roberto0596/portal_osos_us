<?php

namespace App\Http\Controllers\Alumn;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Input;

class HomeController extends Controller
{
	public function index()
	{
        session()->flash("messages","info|El registro fue exitoso");
		return view('Alumn.home.index');
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