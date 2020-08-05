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
        $current_user = Auth::guard('alumn')->user();
        try 
        {
            $data = selectSicoes("Alumno","AlumnoId",Auth::guard('alumn')->user()->id_alumno)[0];
            //validar si un alumno no esta dado de baja
            if (validateDown($current_user->id_alumno)) 
            {
                $validateStatus = validateStatusAlumn($data["AlumnoId"]);

                if ($validateStatus=="error")
                {
                    session()->flash("messages","error|Probablemente no estas en la tabla de inscripción, comunicate con servicios escolares");
                    return redirect()->back();
                }
                
                if ($validateStatus!=false)
                {
                    return view('Alumn.form.index')->with(["estados"=> $estados, 
                                                    "data"=>$data, "currentId"=>Auth::guard('alumn')->user()->id_alumno,
                                                    "group" => $validateStatus]);
                }
                else
                {
                    session()->flash("messages","error|Probablemente no puedes inscribirte este semestre, contactate con servicios escolares");
                    return redirect()->back();
                } 
            }
            else
            {
                $current_user->id_alumno = null;
                $current_user->save();
                session()->flash("messages","info|Lamentamos informarte que fuiste dado de baja de la carrera, Para mas información comunicate al Dpto. de Servicios Escolares");
                return view('Alumn.form.inscription')->with(["estados"=> $estados, 
                                                "user"=>$current_user]);
            }                       
        } 
        catch (\Exception $th) 
        {       
            return view('Alumn.form.inscription')->with(["estados"=> $estados, 
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

        //Edad, el plan de estudio
        $aux = abs(strtotime(date('Y-m-d')) - strtotime($data["FechaNacimiento"]));
        $edad = intval(floor($aux / (365*60*60*24)));
        $planEstudio = $planEstudio["PlanEstudioId"];

        //Matricula
        $tempEnrollment = generateTempMatricula();

        //preparando los datos.
        $array = array('Matricula' => $tempEnrollment,
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
        try
        {
             $this->validate($request,[
                'g-recaptcha-response' => 'required|recaptcha',
            ]);
            $current_user = Auth::guard('alumn')->user();
            $currentId = $current_user->id_alumno;       
            $data = json_decode($request->input('data'));
            if($data != null)
            {
                for ($i = 0; $i < count($data); $i++)
                {
                    updateByIdAlumn($currentId, $data[$i]->name, $data[$i]->value);
                }
            }
            $debit = insertInscriptionDebit($current_user->id_alumno);

            //validacion para saber si es de promedio alto
            if($debit!=0)
            {
                $current_user->inscripcion = 3;
                $current_user->save();
                //generamos los documentos de inscripcion
      			$insertDocuments = insertInscriptionDocuments($current_user->id);
                session()->flash("messages","success|Felicidades por tu promedio, no pagaras inscripción");
                return redirect()->route("alumn.home");
            }
            else
            {
                $current_user->inscripcion = 1;
                $current_user->save();
                session()->flash("messages","success|Se completo la verificación de tu información");
                return redirect()->route("alumn.payment");
            }
        }
        catch(\Exception $e)
        {
            session()->flash("messages","error|Ocurrio un error, no pudimos guardar el registro");
            return redirect()->back();
        }
    }

    public function getMunicipios(Request $request)
    {
        $EstadoId = $request->input("EstadoId");
        $municipios = selectSicoes("Municipio","EstadoId",$EstadoId);
        return response()->json($municipios);
    }
}
