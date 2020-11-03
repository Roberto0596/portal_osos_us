<?php

namespace App\Http\Controllers\AdminPanel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Alumns\FailedRegister;

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

        $query = FailedRegister::select();

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
        $data = getEncGrupoBySemestre($request->get('semestre'), intval(getConfig()->period_id));
        return response()->json($data);
    }

    public function save(Request $request) {
        try {
            $failed = FailedRegister::find($request->get('failedId'));
            $inscription = getInscriptionData($failed->alumn->id_alumno);
            $data1 = updateSicoes("Inscripcion", "Semestre", $request->get('semestre'), "InscripcionId", $inscription["InscripcionId"]);
            $data2 = updateSicoes("Inscripcion", "EncGrupoId", $request->get('encGrupo'), "InscripcionId", $inscription["InscripcionId"]);
            if ($data1 == "error" && $data2 == "error") {
                session()->flash("messages","error|Algo salio mal");
                return redirect()->back();
            }

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