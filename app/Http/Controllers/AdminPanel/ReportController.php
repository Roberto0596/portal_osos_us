<?php

namespace App\Http\Controllers\AdminPanel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PeriodModel;
use App\Models\Alumns\User;
use App\Models\Sicoes\Carrera;
use App\Models\Sicoes\Alumno;

class ReportController extends Controller
{
	public function index()
	{
		$alumns = User::select()->orderBy('email', 'asc')->get();
		$res = [];
		foreach ($alumns as $key => $value) {
			$alumnData = Alumno::find($value->id_alumno);
			if ($alumnData) {
				$carrera = Carrera::find($alumnData->PlanEstudio->CarreraId);
				switch ($value->inscripcion) {
					case 0:
						$status = "Sin llenar formulario";
						break;
					case 1:
						$status = "Sin realizar el pago";
						break;
					case 2:
						$status = "Esperando confirmación";
						break;
					case 3:
						$status = "Seleccion de carga";
						break;
					case 4: 
						$status = "Proceso terminado";
						break;
				}
				array_push($res, [
					"Clave" => $carrera->Nombre,
					"Matricula" => $alumnData->Matricula,
					"Alumno" => $alumnData->FullName,
					"Telefono" => $alumnData->Telefono,
					"Email" => $value->email,
					"Status" => $status
				]);
			}			
		}
		return view('AdminPanel.report.index')->with(["alumn"=>$res]);
    }
}