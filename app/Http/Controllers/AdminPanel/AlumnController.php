<?php

namespace App\Http\Controllers\AdminPanel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Alumns\User;
use App\Models\Alumns\Debit;
use App\Models\Alumns\Notify;
use App\Models\Sicoes\EncGrupo;
use App\Models\Sicoes\Inscripcion;
use App\Models\Sicoes\PlanEstudio;

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
		$alumn->delete();
		session()->flash("messages","success|Todos los registros fueron borrados");
		return redirect()->back();
    }

    public function seeAlumnData(request $response)
	{
		$enrollment = 'sin asignar';
		$group = 'sin asignar';
		$planEstudio = 'sin asignar';
		$alumn = User::find($response->input('id'));

        $inscripcion = Inscripcion::where("AlumnoId", $alumn->id_alumno)->orderBy("InscripcionId", "desc")->first();

		if ($inscripcion) {
            $group = EncGrupo::where("EncGrupoId", $inscripcion->EncGrupoId)->first()->Nombre;
		}

		$array = array(
            'enrollment' => $alumn->sAlumn->Matricula, 
            'group' => $group, 
            'PlanEstudio' => $alumn->sAlumn->PlanEstudio->Clave, 
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
        
        return response()->json([
            "recordsTotal" => User::count(),
            "recordsFiltered" => $filtered,
            "data" => $data
        ]);  
    }

    public function update(Request $request)
    {
        try {
        	$alumn = User::find($request->input('id_alumn'));
            $inscripcion = $alumn->currentInscription();

            if ($request->has('inscription-status') && $request->get('inscription-status')) {
                $alumn->inscripcion = $request->get('inscription-status');
                $alumn->save();
            }

            if ($request->get('is_payment') == 1) {
                $validDebit = Debit::where([["id_alumno", $alumn->id_alumno], ["period_id", getConfig()->period_id]])->first();

                if (!$validDebit) {
                    insertInscriptionDebit($alumn);
                }
            }

        	if ($request->has('PlanEstudioId') && $request->get('PlanEstudioId')) {
                $planEstudio = PlanEstudio::find($request->get('PlanEstudioId'));
                $alumn->sAlumn->PlanEstudioId = $planEstudio->PlanEstudioId;
                $alumn->sAlumn->save();
        	}

        	if ($request->has('EncGrupoId') && $request->get('EncGrupoId')) {
                $inscripcion->EncGrupoId = $request->get('EncGrupoId');
                $inscripcion->save();
         	}

        	if ($request->has('matriculaGenerada') && $request->get('matriculaGenerada') != null) {
                $alumn->sAlumn->Matricula = $request->get('matriculaGenerada');
                $alumn->sAlumn->save();
        	}

            if ($request->has('semestre') && $request->get('semestre') != null) {
                $inscripcion->Semestre = $request->get('semestre');
                $inscripcion->save();
            }

        	session()->flash("messages","success|Todos los datos se guardaron completamente");
        	return redirect()->back();

        } catch(\Exception $e) {
            session()->flash("messages","error|Ocurrió un problema");
            return redirect()->back();
        }
    }

    public function generateEnrollment(Request $request)
    {
    	$enrollment = generateCarnet($request->input('planEstudio'));
    	return response()->json($enrollment);
    }
}