<?php

namespace App\Http\Controllers\AdminPanel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PeriodModel;
use App\Models\Alumns\User;

class ReportController extends Controller
{
	public function index()
	{
		$alumns = User::select()->orderBy('email', 'asc')->get();
		$res = [];
		foreach ($alumns as $key => $value) 
		{
			$alumnData = selectSicoes("Alumno","AlumnoId",$value->id_alumno);
			if ($alumnData!=false)
			{
				$alumnData = $alumnData[0];
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
						$status = "Proceso terminado";
						break;
					case 4: 
						$status = "Carga Asignada";
						break;
				}
				if ($alumnData["ApellidoSegundo"]!=null)
				{
					$nombre = $alumnData["Nombre"]." ".$alumnData["ApellidoPrimero"]." ".$alumnData["ApellidoSegundo"];
				}
				else
				{
					$nombre = $alumnData["Nombre"]." ".$alumnData["ApellidoPrimero"];
				}
				$telefono = $alumnData["Telefono"]!=null?$alumnData["Telefono"]:"Sin telefono";
				array_push($res, ["Matricula"=>$alumnData["Matricula"],
								  "Alumno"=>$nombre,
								  "Telefono"=>$telefono,
								  "Email" => $value->email,
								  "Status"=>$status]);
			}			
		}
		return view('AdminPanel.report.index')->with(["alumn"=>$res]);
    }
}