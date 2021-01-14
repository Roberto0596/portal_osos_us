<?php

namespace App\Http\Controllers\Alumn;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AcademicChargeController extends Controller
{
    public function index()
    {
        return view('Alumn.academic_charge.index');
    }

    public function show($period_id)
    {
        
        $alumn = Auth::guard('alumn')->user();
        $charge = getAcademicChargeByPeriodIdAndAlumnId($period_id,$alumn->id_alumno);
        $data = []; 
        foreach($charge as $key => $value)
        {
            $teacherName = $value['Nombre']." ".$value['ApellidoPrimero']." ".
                                                                $value['ApellidoSegundo'];
            $row = [
                "asignature" => $value['Asignatura'],
                'semester'   => $value['Semestre'],
                'teacher'    => $teacherName,
                'score'      =>  $value['Calificacion'] !== null ? $value['Calificacion'] : 
                                                                                'Sin Calificación'
            ];
           array_push($data ,$row);
    
        }

        $view = view('Alumn.academic_charge.table_body', compact('data'))->render();
        return response()->json($view);
        
        
    }
}
