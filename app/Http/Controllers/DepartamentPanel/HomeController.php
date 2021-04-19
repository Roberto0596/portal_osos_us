<?php

namespace App\Http\Controllers\DepartamentPanel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Logs\Equipment;
use Input;
use Auth;

class HomeController extends Controller
{
	public function index()
	{
        $equipments = Equipment::where("status", 1)->count();
		return view('DepartamentPanel.home.index', [
            'equipments' => $equipments
        ]);
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