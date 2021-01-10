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
			$inscripcion = getInscription($alumn->id_alumno);
			if ($inscripcion) {
				$aux = selectSicoes("EncGrupo","EncGrupoId",$inscripcion["EncGrupoId"]);
				$group = $aux[0]["Nombre"];
			}
		}
		
		$array = array(
            'enrollment' => $enrollment, 
            'group' => $group, 
            'PlanEstudio' => $planEstudio, 
            'semestre' => $inscripcion["Semestre"], 
            "inscription_status" => $alumn->inscripcion);
		return response()->json($array);
    }

    public function show(Request $request)
	{
		$filter = $request->get('search') && isset($request->get('search')['value'])?$request->get('search')['value']:false;
        
        $start = $request->get('start');
        $length = $request->get('length');

        $query = User::select()->orderByDesc("id");
        $filtered = 0;

        if($filter) {
            $query = $query->where(function($query) use ($filter){
                $query->orWhere('name', 'like', '%'. $filter .'%')
                    ->orWhere('lastname', 'like', '%'. $filter . '%')
                    ->orWhere('enrollment', 'like', '%'. $filter . '%')
                    ->orWhere('email', 'like', '%'. $filter . '%');
            });
            $filtered = $query->count();
        } else {
            $filtered = User::count();
        }

        $query->skip($start)->take($length)->get();

        $data = $query->get();

        $res = [];

        foreach($data as $key => $value)
        { 
        	try 
        	{		        
	            $validate = false;
	            if ($value->getSicoesData()) 
	            {
	            	$validate = true;
	            } 

	            array_push($res,[
	                "#" => (count($data)-($key+1)+1),
	                "Matricula" => $value->getMatricula() ? $value->getMatricula() : "sin asignar",
	                "Nombre (s)" => $value->name,
	                "Apellido (s)" => $value->lastname,
	                "Email" => $value->email,
	                "inscripcion" => $value->inscripcion,
	                "validate" => $validate,
	                "id" => $value->id
	            ]);
        	} 
        	catch (\Exception $e) 
        	{
        	}	        	
        }
        return response()->json([
            "recordsTotal" => User::count(),
            "recordsFiltered" => $filtered,
            "data" => $res
        ]);  
    }

    public function update(Request $request)
    {
    	$alumn = User::find($request->input('id_alumn'));
    	$alumnData = selectSicoes("Alumno","AlumnoId",$alumn->id_alumno)[0];
    	$actualizar = [];
        $inscripcion = getInscription($alumn->id_alumno);

        $alumn->inscripcion = $request->get('inscription-status');
        $alumn->save();

        if ($request->get('is_payment') == 1) {
            $validDebit = Debit::where([["id_alumno", $alumn->id_alumno], ["period_id", getConfig()->period_id]])->first();

            if (!$validDebit) {
                insertInscriptionDebit($alumn);
            }
        }

    	if ($request->has('PlanEstudioId')) {
    		array_push($actualizar, updateSicoes("Alumno", "PlanEstudioId", $request->input('PlanEstudioId'), "AlumnoId", $alumnData["AlumnoId"]));
    	}

    	if ($request->has('EncGrupoId')) {
    		array_push($actualizar, updateSicoes("Inscripcion", "EncGrupoId", $request->input('EncGrupoId'),"InscripcionId", $inscripcion["InscripcionId"]));
    	}

    	if ($request->has('matriculaGenerada') && $request->input('matriculaGenerada') != null) {
    		array_push($actualizar, updateSicoes("Alumno", "Matricula", $request->input('matriculaGenerada'), "AlumnoId", $alumnData["AlumnoId"]));
    	}

        if ($request->has('semestre') && $request->input('semestre') != null) {
            array_push($actualizar, updateSicoes("Inscripcion", "Semestre", $request->input('semestre'), "InscripcionId", $inscripcion["InscripcionId"]));
        }

    	if (count($actualizar)==0)
    	{
    		session()->flash("messages","info|Guardado correcto");
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