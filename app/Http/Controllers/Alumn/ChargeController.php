<?php

namespace App\Http\Controllers\Alumn;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Alumns\User;
use DB;
use Input;
use Auth;

class ChargeController extends Controller
{
	public function index()
	{
        $user = User::find(Auth::guard("alumn")->user()->id);
        $getAsignatures = getCurrentAsignatures($user->id_alumno); 

        foreach ($getAsignatures as $key => $value)
        {
            $aux = getDetGrupo($value["AsignaturaId"]);
            array_push($getAsignatures[$key], $aux["DetGrupoId"]);
            array_push($getAsignatures[$key], $aux["ProfesorId"]);
        }
		
        return view('Alumn.charge.index')->with(["asignatures" => $getAsignatures,"user"=>$user]);
	}

    public function save(Request $request,User $user) 
    {
        //periodo actual
        $getCurrentPeriod = selectCurrentPeriod();

        //convertimos el array de las asginaturas que debe llevar y quitamos los campos que no necesitamos
        $currentAsignatures = $this->cleanArray(json_decode($request->input("currentAsignatures"),true),11,1,true,false);

        $array = $this->cleanArray($request->all(),["DetGrupoId","_token","currentAsignatures"],3,false); 

        if (count($array)<1)
        {
            session()->flash("messages","error|Hay un minimo de materias por llevar, favor de no jugar con el sistema");
            return redirect()->back();
        }  

        $successArray = [];
        
        foreach ($currentAsignatures as $key => $value)
        {
            if (in_array($value["DetGrupoId"], $array))
            {
                $baja = 0;
            }
            else
            {
                $baja = 1;
            }

            $temple = array(["Baja"=>$baja,
                            "DetGrupoId"=>$value["DetGrupoId"],
                            "PeriodoId"=>$getCurrentPeriod["PeriodoId"],
                            "AlumnoId"=>$user->id_alumno]);
            
            $insert = insertCharge($temple[0]);
            array_push($successArray, $insert);
        }

        if (in_array(false, $successArray)) {
            deleteCharge($successArray);
            session()->flash("messages","error|No fue posible guardar los datos, favor de reintentar");
            return redirect()->back();
        }

        $user->inscripcion = 4;
        $user->save();

        DB::table('document')->insert([
            ['name' => 'constancia de no adeudo', 'route' => 'alumn.constancia', 'PeriodoId' => $getCurrentPeriod["PeriodoId"], 'alumn_id' => $user->id],
            ['name' => 'cédula de reinscripción', 'route' => 'alumn.cedula', 'PeriodoId' => $getCurrentPeriod["PeriodoId"], 'alumn_id' => $user->id]
        ]);
        session()->flash("messages","success|Terminaste tu registro, felicidades, eres alumno");
        return redirect()->route("alumn.home");
    }

    public function cleanArray($array,$indexstoclean,$rounds,$flag,$mode=true)
    {
        for ($i=0; $i < $rounds; $i++)
        {
            if ($mode)
            {
                if ($flag)
                {
                    foreach ($array as $key => $value) 
                    {
                        unset($array[$key][$indexstoclean[$i]]);
                    }
                }
                else
                {
                    foreach ($array as $key => $value) 
                    {
                        unset($array[$indexstoclean[$i]]);
                    } 
                }
            } 
            else
            {
                $aux = [];
                foreach ($array as $key => $value) 
                {
                   array_push($aux, ["DetGrupoId" => $value[$indexstoclean]]);
                }                
                $array = $aux;
            }
        }
        return $array;
    }
}

// call_user_func_array('array_merge', $aux);