<?php

namespace App\Http\Controllers\AdminPanel;

use Illuminate\Http\Request;
use App\Models\Alumns\HighAverages;
use App\Http\Controllers\Controller;

class HighAveragesController extends Controller
{
    public function index()
    {
        return view('AdminPanel.HighAverages.index');
    }


    public function loadData($periodId)
    {
        $highAverages =  HighAverages::where('periodo_id', $periodId)->get();
        
        $res = [ "data" => []];    
     

        foreach($highAverages as $key => $value)
        {

            $alumnInArray = selectSicoes('Alumno','Matricula', $value->enrollment);

            if(count($alumnInArray) === 0){
                $fullname = "SIN REGISTRO ";
            }else{
                $alumn = $alumnInArray[0];
                $fullname= $alumn['Nombre']." ".$alumn['ApellidoPrimero']." ".$alumn['ApellidoSegundo'];
            }
           
           
           
            $buttons="<div class='btn-group'>
                    <button class='btn btn-danger btnDelete' high_average_id = '".$value->id."' title='Eliminar'><i class='fa fa-times'></i></button>
                </div>";

            array_push($res["data"],[
                (count($highAverages)-($key+1)+1),
                $value->enrollment,
                $fullname,
                $buttons
            ]);
        }

        return response()->json($res);  
    }


   
}
