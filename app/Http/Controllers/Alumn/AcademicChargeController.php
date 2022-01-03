<?php

namespace App\Http\Controllers\Alumn;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Sicoes\Carga;
use App\Library\Sicoes;
use DB;

class AcademicChargeController extends Controller
{
    public function index()
    {
        $alumn = current_user();
        $periods = Sicoes::getAlumnPeriods($alumn->id_alumno);

        return view('Alumn.academic_charge.index')->with([
            "alumn" => $alumn,
            "periods" => $periods
        ]);
    }

    public function show($period_id)
    {        
        $alumn = current_user();

        $carga = Carga::select([
            "Carga.CargaId",
            "Carga.Calificacion",
            "Carga.Baja",
            "Carga.PeriodoId",
            "dg.ProfesorId",
            "dg.AsignaturaId",
            "a.Nombre as Asignatura",
            "a.Semestre",
            DB::raw("CONCAT(' ', p.Nombre, p.ApellidoPrimero, p.ApellidoSegundo) as profesor")
        ])->leftJoin("DetGrupo as dg", "Carga.DetGrupoId", "dg.DetGrupoId")
        ->leftJoin("Asignatura as a", "a.AsignaturaId", "dg.AsignaturaId")
        ->leftJoin("Profesor as p", "p.ProfesorId", "dg.ProfesorId")
        ->leftJoin("Periodo as pe", "pe.PeriodoId", "Carga.PeriodoId")
        ->where("Carga.AlumnoId", $alumn->id_alumno)
        ->where("pe.Clave", $period_id)
        ->get();

        $view = view('Alumn.academic_charge.table_body', compact('carga'))->render();
        return response()->json($view);       
    }
}
