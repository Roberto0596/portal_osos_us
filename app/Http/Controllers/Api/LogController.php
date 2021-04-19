<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Logs\Equipment;
use Validator;

class LogController extends Controller
{
	public function watchState(Request $request) {

		$validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

		try {
			$equipment = Equipment::find($request->get('id'));
			$response = [];

			if ($equipment) {

				if ($equipment->status == 1) {
					$response = ["message" => "Equipo desbloqueado", "status" => "1"];
				} else {
					$response = ["message" => "Equipo bloqueado", "status" => "0"];
				}

			} else {
				$response = ["message" => "Equipo no encontrado", "status" => "0"];
			}

			return response()->json($response, 200);

		} catch(\Exception $e) {
			return response()->json(["message" => "error", "status" => "0"], 500);
		}
	}
}