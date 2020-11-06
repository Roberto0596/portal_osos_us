<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Alumns\User;
use Input;

class DebitController extends Controller
{
	public function verifyDebit(Request $request)
	{
		$file = fopen(public_path()."/orden.txt", "w+b");
        fwrite($file, json_encode($request->all()));
        fclose($file);
        return response()->json(["status" => "success"],200);
	}
}