<?php

namespace App\Http\Controllers\AdminPanel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Alumns\FailedRegister;
use App\Library\Sicoes;
use App\Models\Sicoes\Inscripcion;

class FailedRegisterController extends Controller
{
	public function index()
	{
		return view('AdminPanel.failedREgister.index');
    }

    public function show(Request $request) {

        $start = $request->get('start');

        $length = $request->get('length');

        $filtered = 0;

        $query = FailedRegister::where("status",0);

        $filtered = $query->count();

        $query->skip($start)->take($length)->get();

        $data = $query->get();

        $res = [];

        foreach ($data as $key => $value) {
            array_push($res, [
                "#" => $key+1,
                "Alumno" => $value->alumn->name. ' ' .$value->alumn->lastname,
                "Periodo" => $value->period->clave,
                "Mensaje" => $value->message,
                "status" => $value->status,
                "Fecha" => $value->created_at,
                "id" => $value->id
            ]);
        }

        return response()->json([
            "recordsTotal" => FailedRegister::count(),
            "recordsFiltered" => $filtered,
            "data" => $res
        ]);
    }

    public function encGrupo(Request $request) {
        return response()->json(Sicoes::getGroupsBySemestre(getConfig()->period_id, $request->get('semestre')));
    }

    public function save(Request $request) {
        try {
            $failed = FailedRegister::find($request->get('failedId'));
            $inscription = Inscripcion::where("AlumnoId", $failed->alumn->id_alumno)
                                        ->orderBy("InscripcionId", "desc")
                                        ->first();
            $inscription->Semestre = $request->get("semestre");
            $inscription->EncGrupoId = $request->get('encGrupo');
            $inscription->save();
            $failed->status = 1;
            $failed->save();
            session()->flash("messages","success|El problema fue solucionado");
            return redirect()->back();
        } catch(\Exception $e) {
            session()->flash("messages","error|Algo salio mal");
            return redirect()->back();
        }
    }
}