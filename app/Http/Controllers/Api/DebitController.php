<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Alumns\User;
use App\Models\Alumns\Debit;
use App\Models\Alumns\Document;
use Input;

class DebitController extends Controller
{
	public function verifyDebit(Request $request)
	{
		try {
			$data = $request->all();
	        $is_paid = $data["type"];

	        if ($is_paid == "order.paid" || $is_paid = "charge.created") {
	        	try {
					$id_order = $data["data"]["object"]["charges"]["data"][0]["order_id"];
				} catch(\Exception $e) {
					$id_order = $data["data"]["object"]["id"];
				}

	        	$debits = Debit::where("id_order", $id_order)->get();
	        	
	        	$alumn = User::where("id_alumno","=",$debits[0]->id_alumno)->first();

		        foreach ($debits as $key => $value) {
		        	if ($value->payment_method != "card") {
		        		if ($value->debit_type_id == 1) {
		        			$register = makeRegister($alumn);
		        		}
		        		if ($value->has_file_id != null) {
			              	$document = Document::find($value->has_file_id);
			              	$document->payment = 1;
			              	$document->save();
			            }
		        		$value->status = 1;
		        		$value->save();
		        	}
		        }

		        addNotify("pago realizado con exito",$alumn->id, "alumn.debit");
	        }      
	        return response()->json(["status" => "success"],200);
		} catch(\Exception $e) {
			addLog($e->getFile()." ".$e->getLine()." ".$e->getMessage());
			return response()->json(["status" => "error"],500);
		}
	}
}