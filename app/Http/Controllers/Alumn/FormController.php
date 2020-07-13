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
        try 
        {
            $currentId = Auth::guard('alumn')->user()->id_alumno;
            $data = getDataByIdAlumn($currentId); 
            $charge = selectSicoes("Carga","AlumnoId",$data["AlumnoId"]);  
            $charge = $charge[count($charge)-1];
            $detGrupo = selectSicoes("DetGrupo","DetGrupoId",$charge["DetGrupoId"])[0];
            $group =  selectSicoes("EncGrupo","EncGrupoId",$detGrupo["EncGrupoId"])[0];  
            return view('Alumn.form.index')->with(["estados"=> $estados , 
                                                "municipios"=> $municipios , 
                                                "data"=>$data, "currentId"=>$currentId,
                                                "group" => $group]);
        } 
        catch (\Exception $th) 
        {
            $current_user = Auth::guard('alumn')->user();   
            return view('Alumn.form.inscription')->with(["estados"=> $estados , 
                                                "municipios"=> $municipios,
                                                "user"=>$current_user]);
        }
        
        
        if($currentId != null)
        {
             
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
        $carrera = selectSicoes("Carrera","CarreraId",$data["Carrera"])[0];
        $ultimoAlumno = getLastThing("Alumno","PlanEstudioId",$planEstudio["PlanEstudioId"],"AlumnoId");

        //preparar la matricula.
        $sum = substr($ultimoAlumno["Matricula"],-4) + 1;
        $lastDate = strlen($sum)==2? "00".$sum: $sum;
        $date = getDate();
        $first = substr($date["year"], -2);

        //Edad, Matricula y el plan de estudio
        $aux = abs(strtotime(date('Y-m-d')) - strtotime($data["AñoNacimiento"]));
        $edad = intval(floor($aux / (365*60*60*24)));
        $matricula = $first."-".$carrera["Clave"]."-".$lastDate;
        $planEstudio = $planEstudio["PlanEstudioId"];

        //preparando los datos.
        $array = array('Matricula' => $matricula,
                       'Nombre' => strtoupper($data["Nombre"]),
                       'ApellidoPrimero'=> strtoupper($data["ApellidoPrimero"]),
                       'ApellidoSegundo' => strtoupper($data["ApellidoSegundo"]),
                       'Regular'=> chr(1),
                        'Tipo'=>chr(0),
                        'Curp'=>strtoupper($data["Curp"]),
                        'Genero'=>$data["Genero"],
                        'FechaNacimiento'=>$data["AñoNacimiento"],
                        'Edad' => $edad,
                        'MunicipioNac' => $data["MunicipioNac"],
                        'EstadoNac' => $data["EstadoNac"],
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
                        'TrabajaActualmente'=>chr($data["TrabajaActualmente"]),
                        'Puesto'=>array_key_exists("Puesto",$data)?strtoupper($data["Puesto"]):null,
                        'SueldoMensualAlumno'=>array_key_exists("SueldoMensualAlumno",$data)?$data["SueldoMensualAlumno"]:null,
                        'DeportePractica'=>array_key_exists("DeportePractica",$data)?strtoupper($data["DeportePractica"]):null,
                        'Deportiva'=>chr(0),
                        'Cultural'=>chr(0),
                        'Academica'=>chr(0),
                        'TransporteUniversidad'=>chr($data["TransporteUniversidad"]),
                        'Transporte'=>array_key_exists("Transporte",$data)?chr($data["Transporte"]):chr(0),
                        'ActaNacimiento'=>0,
                        'CertificadoBachillerato'=>0,
                        'Baja'=>chr(0),
                        'PlanEstudioId'=>$planEstudio,
                        'CirugiaMayor'=>chr(0),
                        'CirugiaMenor'=>chr(0),
                        "Hijo"=>chr(0),
                        'Egresado'=>chr(0)
                    );
        $insert = InsertAlumn($array);
        dd($insert);
        if ($insert!=false)
        {
            //guardamos el nuevo correo del usuario
            $user->email = "a".$first.$carrera["Clave"].$lastDate."@unisierra.edu.mx";
            $user->inscripcion=1;
            $user->save();
            session()->flash("messages","success|Ya casi eres un alumno unisierra");
            return redirect()->route("Alumn.payment");
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
        
        if( $captcha != null)
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
            DB::table('debit')->insert(
                ['concept' => 'Pago de colegiatura',
                 'amount' => 1950.00,
                 'admin_id'=> 3,
                 'id_alumno'=>$currentId,
                 'created_at'=>$mytime->toDateTimeString(),
                 'updated_at'=>$mytime->toDateTimeString()]
            );
            return response()->json('ok');
        }
        else
        {
            return response()->json('error');
        }
    }
}
