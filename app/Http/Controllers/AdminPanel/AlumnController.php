<?php

namespace App\Http\Controllers\AdminPanel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Alumns\User;

class AlumnController extends Controller
{
	public function index()
	{
		return view('AdminPanel.alumn.index');
    }

    public function show()
	{
		$problems = User::all();
        $res = [ "data" => []];

        foreach($problems as $key => $value)
        {  
        	$img = "<img src='".asset($value->photo)."'>";

	        switch ($value->inscripcion) {
	        	case 0:
	        		$status="Sin llenar formulario";
	        		break;
	        	case 1:
	        		$status="Sin realizar el pago";
	        		break;
	        	case 2:
	        		$status="Esperando confirmación de pago";
	        		break;
	        	case 3:
	        		$status="Proceso terminado";
	        		break;
	        }          
            array_push($res["data"],[
                (count($problems)-($key+1)+1),
                $value->name,
                $value->lastname,
                $value->email,
                $img,
                $status,
                $value->created_at,
            ]);
        }
        return response()->json($res);  
    }
}