<?php

namespace App\Http\Controllers\Alumn;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Input;

class PaymentController extends Controller
{
	public function index()
	{
		return view('Alumn.payment.index');
	}

	public function card_method() 
	{
        return view('Alumn.payment.card');
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