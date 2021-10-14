<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Alumns\User;
use App\Models\Alumns\Debit;
use App\Models\Alumns\Document;
use App\Library\Inscription;
use App\Enum\DebitStatus;
use Input;

class DebitController extends Controller
{
	public function verifyDebit(Request $request)
	{
		try {
			$data = $request->all();

	        $is_paid = $data["type"];

	        if ($is_paid == "order.paid") {
	        	
	        	$id_order = $this->extractIdOrder($data);

				$debits = Debit::where("id_order", $id_order)->get();

				if($debits->count() > 0) {
						        	
					$alumn = User::where("id_alumno","=",$debits[0]->id_alumno)->first();

					foreach ($debits as $key => $value) {

						if ($value->status == Debit::getStatus(DebitStatus::pending())) {

							if ($value->payment_method != "card") {

								if ($value->debit_type_id == 1) {
									$register = Inscription::makeRegister($alumn->sAlumn);
									$value->setForeignValues();
								}

								$value->validate(Debit::getStatus(DebitStatus::paid()));
							}

						}
					}
					addNotify("pago realizado con exito",$alumn->id, "alumn.debit");
				}

				return response()->json(["status" => "success"], 200);	        	
	        }

		} catch(\Exception $e) {
			addLog($e->getFile()." ".$e->getLine()." ".$e->getMessage());
			return response()->json(["status" => "error"],500);
		}
	}

	public function extractIdOrder($data) {
		$id_order = null;
		try {
			$id_order = $data["data"]["object"]["charges"]["data"][0]["order_id"];
		} catch(\Exception $e) {
			$id_order = $data["data"]["object"]["order_id"];
		}

		return $id_order;
	}
}