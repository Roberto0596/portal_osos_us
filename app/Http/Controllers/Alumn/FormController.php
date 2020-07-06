<?php

namespace App\Http\Controllers\Alumn;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Alumns\User;
use App\Models\Alumns\Debit;
use App\Http\Requests\CreateUserRequest;
use DB;

class FormController extends Controller
{
    public function index()
    {
        $estados = getItemClaveAndNamesFromTables("Estado");
        $municipios = getItemClaveAndNamesFromTables("Municipio");
        $currentId = Auth::guard('alumn')->user()->id_alumno;
        $data = null;
        if($currentId != null)
        {
            $data = getDataByIdAlumn($currentId); 
            $group = selectSicoes("EncGrupo","planestudioid",$data["PlanEstudioId"])[0];      
        }
        $mytime = \Carbon\Carbon::now();
        DB::table('debit')->insert(
            ['concept' => 'Pago de colegiatura',
             'amount' => 1950.00,
             'admin_id'=> 3,
             'id_alumno'=>$currentId,
             'created_at'=>$mytime->toDateTimeString(),
             'updated_at'=>$mytime->toDateTimeString()]
        );

        return view('Alumn.form.index')->with(["estados"=> $estados , 
                                                "municipios"=> $municipios , 
                                                "data"=>$data, "currentId"=>$currentId,
                                                "group" => $group]);
    }

    public function save(Request $request)
    {
        $current_user = Auth::guard('alumn')->user();
        $currentId = $current_user->id_alumno;       
        $dataAsString = $request->input('data');
        $dataArray = json_decode($dataAsString);
        $captcha = $request->input('recaptcha');
        
        if( $captcha != null)
        {
            if($dataArray != null)
            {
                // for ($i = 0; $i < count($dataArray); $i++)
                // {
                //     updateByIdAlumn($currentId, $dataArray[$i]->name, $dataArray[$i]->value);
                // }
            }
            $current_user->inscripcion = 1;
            $current_user->save();
            return response()->json('ok');
        }
        else
        {

            return response()->json('error');
        }
    }
}
