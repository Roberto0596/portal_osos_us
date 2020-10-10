<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Alumns\User;
use Input;

class WebsiteController extends Controller
{
	public function index()
	{
        $img = \Image::make(public_path().'\img\temple\avatar.jpg');    
        $img->insert(public_path().'\img\temple\unisierra.png','bottom-right',10, 10); 
        $img->save();
		return view('Website.register');
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