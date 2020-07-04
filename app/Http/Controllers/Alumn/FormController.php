<?php

namespace App\Http\Controllers\Alumn;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Alumns\User;
use App\Http\Requests\CreateUserRequest;


class FormController extends Controller
{
    public function index(){
     
        $estados = getItemClaveAndNamesFromTables("estado");
        $municipios = getItemClaveAndNamesFromTables("municipio");
        $carreras = getCarreras();


        $currentId = Auth::guard('alumn')->user()->id_alumno;
        $data = null;

        if($currentId != null){

            $data = getDataByIdAlumn($currentId);
            
           
            
        }

        return view('Alumn.form.index')->with(["estados"=> $estados , "municipios"=> $municipios , "data"=>$data , "carreras" => $carreras]);
    }

    public function save(Request $request){

        $currentId = Auth::guard('alumn')->user()->id_alumno;
        $dataAsString = $request->input('data');

        $dataArray = json_decode($dataAsString);


       if($dataArray != null){
        for ($i = 0; $i < count($dataArray); $i++) {
            updateByIdAlumn($currentId, $dataArray[$i]->name, $dataArray[$i]->value);
        }
       }


        
        return response()->json('ok');



    }
}
