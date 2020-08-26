<?php

namespace App\Http\Controllers\AdminPanel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Alumns\User;
use App\Models\Alumns\Debit;
use App\Models\Alumns\Notify;

class AlumnController extends Controller
{
	public function index()
	{
		return view('AdminPanel.alumn.index');
    }

    public function delete($id)
	{
		$alumn = User::find($id);
		Debit::where("id_alumno","=",$alumn->id_alumno)->delete();
		User::destroy($id);
		session()->flash("messages","success|Todos los registros fueron borrados");
		return redirect()->back();
    }

    public function seeAlumnData(request $response)
	{
		$enrollment='sin asignar';
		$group='sin asignar';
		$planEstudio='sin asignar';
		$alumn = User::find($response->input('id'));
		$alumnData = selectSicoes("Alumno","AlumnoId",$alumn->id_alumno);

		if ($alumnData) {
			$aux = selectSicoes("PlanEstudio","PlanEstudioId",$alumnData[0]["PlanEstudioId"]);
			$planEstudio = $aux[0]["Clave"];
			$enrollment = $alumnData[0]["Matricula"];
			$inscripcion =getInscription($alumn->id_alumno);
			if ($inscripcion) {
				$aux = selectSicoes("EncGrupo","EncGrupoId",$inscripcion["EncGrupoId"]);
				$group = $aux[0]["Nombre"];
			}
		}
		
		$array = array('enrollment' => $enrollment, 'group' => $group, 'PlanEstudio' => $planEstudio);
		return response()->json($array);
    }

    public function show()
	{
		$alumns = User::all();
        $res = [ "data" => []];

        foreach($alumns as $key => $value)
        { 
        	try 
        	{
        	 	$img = "<img src='".asset($value->photo)."'>";
		        switch ($value->inscripcion) {
		        	case 0:
		        		$status="Sin llenar formulario";
		        		break;
		        	case 1:
		        		$status="Sin realizar el pago";
		        		break;
		        	case 2:
		        		$status="Esperando confirmación de pago";
		        		break;
		        	case 3:
		        		$status="Proceso terminado";
		        		break;
		        	case 4:
		        		$status="Carga asignada";
		        		break;
		        } 

		        $buttons = "<div class='btn-group'>";
	            $sicoesData = selectSicoes("Alumno","AlumnoId",$value->id_alumno);

	            if ($sicoesData) 
	            {
	            	$enrollment = $sicoesData[0]["Matricula"];
	            	$buttons.="<button class='btn btn-warning btnUpdateAlumn' data-toggle='modal' data-target='#modal-edit-alumn' title='editar alumno' alumnId = '".$value->id."' title='Imprimir'>
	           			<i class='fa fa-pen' style='color:white'></i></button>";
	            } 
	            else 
	            {
	            	$enrollment = "sin asignar";
	            }

	            $buttons.="
	            <button class='btn btn-danger btnDeleteAlumn' title='Eliminar alumno' alumnId = '".$value->id."' title='Imprimir'>
	            <i class='fa fa-times'></i></button>
	            </div>"; 

	            array_push($res["data"],[
	                (count($alumns)-($key+1)+1),
	                $enrollment,
	                $value->name,
	                $value->lastname,
	                $value->email,
	                $status,
	                $value->created_at,
	                $buttons,
	            ]);
        	} 
        	catch (\Exception $e) 
        	{
        	}	        	
        }
        return response()->json($res);  
    }

    public function update(Request $request)
    {
    	$alumn = User::find($request->input('id_alumn'));
    	$alumnData = selectSicoes("Alumno","AlumnoId",$alumn->id_alumno)[0];
    	$actualizar = [];

    	if ($request->has('PlanEstudioId')) {
    		array_push($actualizar, updateSicoes("Alumno", "PlanEstudioId", $request->input('PlanEstudioId'), "AlumnoId", $alumnData["AlumnoId"]));
    	}

    	if ($request->has('EncGrupoId')) {
    		$inscripcion =getInscription($alumn->id_alumno);
    		array_push($actualizar, updateSicoes("Inscripcion", "EncGrupoId", $request->input('EncGrupoId'),"InscripcionId", $inscripcion["InscripcionId"]));
    	}

    	if ($request->has('matriculaGenerada') && $request->input('matriculaGenerada') != null) {
    		array_push($actualizar, updateSicoes("Alumno", "Matricula", $request->input('matriculaGenerada'), "AlumnoId", $alumnData["AlumnoId"]));
    	}

    	if (count($actualizar)==0)
    	{
    		session()->flash("messages","info|No iba ningun dato para actualizar");
    		return redirect()->back();
    	}
    	else if (in_array("error", $actualizar)) 
    	{
    		session()->flash("messages","warning|Algunos datos no se guardaron");
    		return redirect()->back();
    	}
    	else
    	{
    		session()->flash("messages","success|Todos los datos se guardaron completamente");
    		return redirect()->back();
    	}
    }

    public function generateEnrollment(Request $request)
    {
    	$enrollment = generateCarnet($request->input('planEstudio'));
    	return response()->json($enrollment);
    }
}