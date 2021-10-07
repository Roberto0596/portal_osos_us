<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Logs\Equipment;
use Validator;

class LogController extends Controller
{
	public function watchState(Request $request) {

		$response = [];

		try {
			$equipment = Equipment::find($request->get('id'));

			if ($equipment) {

				if ($equipment->status == 1) {
					$response = [
						"message" => "Equipo desbloqueado", 
						"status" => "1",
						"user" => ($equipment->tempUse) ? $equipment->tempUse->alumn->FullName : null
					];

				} else {
					$response = [
						"message" => "Equipo bloqueado", 
						"status" => "0",
						"user" => "null"
					];
				}

			} else {
				$response = [
					"message" => "Equipo no encontrado", 
					"status" => "0",
					"user" => "null"
				];
			}

		} catch(\Exception $e) {
			$response = [
				"message" => "error",
				"status" => "0",
				"user" => "null"
			];
		}

		return response()->json($response, 200);
	}
}