<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Alumns\User;
use App\Models\Alumns\Debit;
use Input;

class DebitController extends Controller
{
	public function verifyDebit(Request $request)
	{
		try {
			$data = (object) $request->all();
	        $is_paid = $data["type"];

	        if ($is_paid == "order.paid" || $is_paid = "charge.created") {
	        	try {
					$id_order = $data->data->object->charges->data[0]->order_id;
				} catch(\Exception $e) {
					$id_order = $data->data->object->order_id;
				}

	        	$debits = Debit::where("id_order", $id_order)->get();
	        
		        foreach ($debits as $key => $value) {
		        	if ($value->payment_method != "card") {
		        		if ($value->debit_type_id == 1) {
		        			$alumn = User::where("id_alumno","=",$value->id_alumno)->first();
		                	$register = makeRegister($alumn);
		        		}
		        		$value->status = 1;
		        		$value->save();
		        	}
		        }
	        }      
	        return response()->json(["status" => "success"],200);
		} catch(\Exception $e) {
			addLog($e->getFile()." ".$e->getLine()." ".$e->getMessage());
			return response()->json(["status" => "error"],500);
		}
	}
}