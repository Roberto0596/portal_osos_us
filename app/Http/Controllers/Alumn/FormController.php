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
        try 
        {
            $data = selectSicoes("Alumno","AlumnoId",Auth::guard('alumn')->user()->id_alumno)[0];
            // $group = getAlumnGroup(Auth::guard('alumn')->user()->id_alumno);
            $group = ["Nombre" => "Hola"];
            if ($group!=false)
            {
                return view('Alumn.form.index')->with(["estados"=> $estados , 
                                                "data"=>$data, "currentId"=>Auth::guard('alumn')->user()->id_alumno,
                                                "group" => $group]);
            }
            else
            {
                session()->flash("messages","error|Posiblemente los grupos no han sido creados");
                return redirect()->back();
            }
            
        } 
        catch (\Exception $th) 
        {
            $current_user = Auth::guard('alumn')->user();   
            return view('Alumn.form.inscription')->with(["estados"=> $estados , 
                                                "user"=>$current_user]);
        }
    }

    public function saveInscription(Request $request)
    {
        // $this->validate($request,[
        //     'g-recaptcha-response' => 'required|recaptcha',
        // ]);
        $current_user = Auth::guard('alumn')->user();
        $data = $request->input();

        //traer el plan de esudio y el ultimo alumno de esa carrera con ese plan de estudios
        $planEstudio = getLastThing("planEstudio","CarreraId",$data["Carrera"],"PlanEstudioId");

        //Edad, Matricula y el plan de estudio
        $aux = abs(strtotime(date('Y-m-d')) - strtotime($data["FechaNacimiento"]));
        $edad = intval(floor($aux / (365*60*60*24)));
        $planEstudio = $planEstudio["PlanEstudioId"];

        //preparando los datos.
        $array = array('Matricula' => 'Aspirante',
                   'Nombre' => strtoupper($data["Nombre"]),
                   'ApellidoPrimero'=> strtoupper($data["ApellidoPrimero"]),
                   'ApellidoSegundo' => array_key_exists("ApellidoSegundo",$data)?strtoupper($data["ApellidoSegundo"]):null,
                   'Regular'=> 1,
                    'Tipo'=>0,
                    'Curp'=>array_key_exists("Curp",$data)?strtoupper($data["Curp"]):null,
                    'Genero'=>$data["Genero"],
                    'FechaNacimiento'=>$data["FechaNacimiento"],
                    'Edad' => $edad,
                    'MunicipioNac' => array_key_exists("MunicipioNac",$data)?strtoupper($data["MunicipioNac"]):null,
                    'EstadoNac' => array_key_exists("EstadoNac",$data)?strtoupper($data["EstadoNac"]):null,
                    'EdoCivil' => $data["EdoCivil"],
                    'Estatura' => 0,
                    'Peso' => 0,
                    'TipoSangre' => $data["TipoSangre"],
                    'Alergias'=>array_key_exists("Alergias",$data)?strtoupper($data["Alergias"]):null,
                    'Padecimiento'=>array_key_exists("Padecimiento",$data)?strtoupper($data["Padecimiento"]):null,
                    'ServicioMedico'=>$data["ServicioMedico"],
                    'NumAfiliacion'=>array_key_exists("NumAfiliacion",$data)?$data["NumAfiliacion"]:null,
                    'Domicilio'=>array_key_exists("Domicilio",$data)?strtoupper($data["Domicilio"]):null,
                    'Colonia'=>array_key_exists("Colonia",$data)?strtoupper($data["Colonia"]):null,
                    'Localidad'=>array_key_exists("Localidad",$data)?strtoupper($data["Localidad"]):null,
                    'MunicipioDom' =>array_key_exists("MunicipioDom",$data)?$data["MunicipioDom"]:null,
                    'EstadoDom'=>array_key_exists("EstadoDom",$data)?$data["EstadoDom"]:null,
                    'CodigoPostal'=>array_key_exists("CodigoPostal",$data)?$data["CodigoPostal"]:null,
                    'Telefono'=>array_key_exists("Telefono",$data)?$data["Telefono"]:null,
                    'Email'=>array_key_exists("Email",$data)?$data["Email"]:null,
                    'EscuelaProcedenciaId'=>array_key_exists("EscuelaProcedenciaId",$data)?$data["EscuelaProcedenciaId"]:null,
                    'AnioEgreso'=>array_key_exists("AnioEgreso",$data)?$data["AnioEgreso"]:null,
                    'PromedioBachiller'=>array_key_exists("PromedioBachiller",$data)?$data["PromedioBachiller"]:null,
                    'ContactoEmergencia' => array_key_exists("ContactoEmergencia",$data)?strtoupper($data["ContactoEmergencia"]):null,
                    'ContactoDomicilio'=>array_key_exists("ContactoDomicilio",$data)?strtoupper($data["ContactoDomicilio"]):null,
                    'ContactoTelefono'=>array_key_exists("ContactoTelefono",$data)?strtoupper($data["ContactoTelefono"]):null,
                    'TutorNombre'=> array_key_exists("TutorNombre",$data)?strtoupper($data["TutorNombre"]):null,
                    'TutorDomicilio'=>array_key_exists("TutorDomicilio",$data)?strtoupper($data["TutorDomicilio"]):null,
                    'TutorTelefono'=>array_key_exists("TutorTelefono",$data)?strtoupper($data["TutorTelefono"]):null,
                    'TutorOcupacion'=>array_key_exists("TutorOcupacion",$data)?strtoupper($data["TutorOcupacion"]):null,
                    'TutorSueldoMensual'=>array_key_exists("TutorSueldoMensual",$data)?$data["TutorSueldoMensual"]:null,
                    'MadreNombre'=>array_key_exists("MadreNombre",$data)?strtoupper($data["MadreNombre"]):null,
                    'MadreDomicilio'=>array_key_exists("MadreDomicilio",$data)?strtoupper($data["MadreDomicilio"]):null,
                    'MadreTelefono'=>array_key_exists("MadreTelefono",$data)?strtoupper($data["MadreTelefono"]):null,
                    'TrabajaActualmente'=>$data["TrabajaActualmente"],
                    'Puesto'=>array_key_exists("Puesto",$data)?strtoupper($data["Puesto"]):null,
                    'SueldoMensualAlumno'=>array_key_exists("SueldoMensualAlumno",$data)?$data["SueldoMensualAlumno"]:null,
                    'DeportePractica'=>array_key_exists("DeportePractica",$data)?strtoupper($data["DeportePractica"]):null,
                    'Deportiva'=>0,
                    'Cultural'=>0,
                    'Academica'=>0,
                    'TransporteUniversidad'=>array_key_exists("TransporteUniversidad",$data)?1:0,
                    'Transporte'=>array_key_exists("Transporte",$data)?1:0,
                    'ActaNacimiento'=>0,
                    'CertificadoBachillerato'=>0,
                    'Baja'=>0,
                    'PlanEstudioId'=>$planEstudio,
                    'CirugiaMayor'=>0,
                    'CirugiaMenor'=>0,
                    'Hijo'=>0,
                    'Egresado'=>0
                );
        $insert = InsertAlumn($array);
        if ($insert!=false)
        {
            //guardamos el nuevo correo del usuario
            $current_user->inscripcion=1;
            $current_user->id_alumno = $insert;
            $current_user->save();
            $mytime = \Carbon\Carbon::now();
            $debit = insertInscriptionDebit($current_user->id_alumno);
            session()->flash("messages","success|Ya casi eres un alumno unisierra");
            return redirect()->route("alumn.payment");
        }       
        else
        {
            session()->flash("messages","error|No pudimos guardar los datos");
            return redirect()->back();
        }
    }

    public function save(Request $request)
    {
        $current_user = Auth::guard('alumn')->user();
        $currentId = $current_user->id_alumno;       
        $dataAsString = $request->input('data');
        $dataArray = json_decode($dataAsString);
        $captcha = $request->input('recaptcha');
        
        if($captcha == null)
        {
            try
            {
                if($dataArray != null)
                {
                    for ($i = 0; $i < count($dataArray); $i++)
                    {
                        updateByIdAlumn($currentId, $dataArray[$i]->name, $dataArray[$i]->value);
                    }
                }
                $current_user->inscripcion = 1;
                $current_user->save();
                $mytime = \Carbon\Carbon::now();
                $debit = insertInscriptionDebit($current_user->id_alumno);
                return response()->json('ok');
            }
            catch(\Exception $e)
            {
                return response()->json('error');
            }
        }
        else
        {
            return response()->json('error');
        }
    }

    public function getMunicipios(Request $request)
    {
        $EstadoId = $request->input("EstadoId");
        $municipios = selectSicoes("Municipio","EstadoId",$EstadoId);
        return response()->json($municipios);
    }
}
