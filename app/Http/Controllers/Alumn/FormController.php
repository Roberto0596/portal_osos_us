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
     
        $estados = getItemClaveAndNamesFromTables("Estado");
        $municipios = getItemClaveAndNamesFromTables("Municipio");
        


        $currentId = Auth::guard('alumn')->user()->id_alumno;
        $data = null;

        if($currentId != null){

            $data = getDataByIdAlumn($currentId);
           
           
          
               
          
            
           
            
        }

        return view('Alumn.form.index')->with(["estados"=> $estados , "municipios"=> $municipios , "data"=>$data, "currentId"=>$currentId]);
    }

    public function save(Request $request){

        $currentId = Auth::guard('alumn')->user()->id_alumno;
        
        $dataAsString = $request->input('data');
        $dataArray = json_decode($dataAsString);
        $captcha = $request->input('recaptcha');


      

        if( $captcha != null){

            if($dataArray != null){
                for ($i = 0; $i < count($dataArray); $i++) {
                    updateByIdAlumn($currentId, $dataArray[$i]->name, $dataArray[$i]->value);
                }
            }
            return response()->json('ok');

        

        }else{

            return response()->json('error');
        }


        

      



        
       



    }
}
