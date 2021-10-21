<?php

namespace App\Http\Controllers\Alumn;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Alumns\User;
use App\Models\Alumns\Debit;
use App\Http\Requests\CreateUserRequest;
use App\Models\Sicoes\Alumno;
use App\Models\Sicoes\Estado;
use App\Models\Sicoes\Municipio;
use App\Library\Sicoes;
use DB;
use Auth;

class FormController extends Controller
{
    public function indexForm()
    {
        $current_user = current_user();

        if (Sicoes::validateDown($current_user->id_alumno)) {

            $checkGroup = Sicoes::checkGroupData($current_user->id_alumno);
          
            if ($checkGroup == false || $checkGroup == null) {

                $checkGroup = [
                    "Nombre" => "ninguno",
                    "PeriodoId" => selectCurrentPeriod()->clave,
                    "Semestre" => "sin asignar"
                ];
            } 

            return view('Alumn.form.index')->with([
                "data" => $current_user->sAlumn, 
                "currentId" => $current_user->id_alumno,
                "group" => $checkGroup
            ]);
        } else {
            session()->flash("messages","info|No podemos inscribirte en esta carrera, Para mas información comunicate al Dpto. de Servicios Escolares");
            return redirect()->back();
        }                       
    }

    public function indexInscription()
    {
        return view('Alumn.form.inscription')->with([
            "user" => current_user()
        ]);
    }

    public function saveInscription(Request $request)
    {
        $this->validate($request,[
            'g-recaptcha-response' => 'required|recaptcha',
        ]);

        $current_user = current_user();

        $data = $request->except(["_token"]);

        $correct_format = Sicoes::constructAlumnArray($data);
        
        $alumn = new Alumno();

        foreach ($correct_format as $key => $value) {
            $alumn->$key = $value;
        }

        $alumn->save();
        $current_user->id_alumno = $alumn->AlumnoId;
        $current_user->save();

        insertInscriptionDebit($current_user);

        session()->flash("messages","success|Finalizó la recolección de tus datos.");
        return redirect()->route("alumn.payment");
    }

    public function save(Request $request)
    {       
        try
        {
            $this->validate($request,[
                'g-recaptcha-response' => 'required|recaptcha',
            ]);

            $current_user = current_user();
            $data = json_decode($request->input('data'), true);

            if($data != null) {

                $alumn = Alumno::find($current_user->id_alumno);

                foreach ($data as $key => $value) {
                    $col = $value["name"];
                    $alumn->$col = $value["value"];
                }

                $alumn->save();
            }

            //validate debit 
            $validate = Debit::where("id_alumno", $current_user->id_alumno)
                                ->where("period_id", selectCurrentPeriod()->id)
                                ->where("debit_type_id", 1)
                                ->first();

            if (!$validate) {
                $debit = insertInscriptionDebit($current_user);
            } else {
                $current_user->nextStep();
            }

            if (isset($debit)) {
                session()->flash("messages","success|".$debit["message"]);
            } else {
                session()->flash("messages","success|Datos guardados con exito");
            }

            return redirect()->route("alumn.payment");
            
        } catch(\Exception $e) {
            session()->flash("messages","error|Ocurrio un error, no pudimos guardar el registro.");
            return redirect()->back();
        }
    }

    public function getMunicipios(Request $request) {
        $municipios = Municipio::where("EstadoId",$request->input("EstadoId"))->get();
        return response()->json($municipios);
    }
}
