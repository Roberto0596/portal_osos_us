<?php namespace App\Library;

use App\Models\Sicoes\PlanEstudio;
use App\Models\Sicoes\Alumno;

class Sicoes {

    /**
     * join Alumn info with carrer, state and plan de estudio and return object
     *
     * @param  int $id_alumno|required
     *
     * @return Collection $alumn
     */
    public static function getDataAlumnDebit($id_alumno) {
        $alumn = Alumno::leftJoin("PlanEstudio as p", "Alumno.PlanEstudioId", "p.PlanEstudioId")
                    ->leftJoin("Carrera as c", "p.CarreraId", "c.CarreraId")
                    ->leftJoin("Estado as e", "Alumno.EstadoDom", "e.EstadoId")
                    ->where("Alumno.AlumnoId", $id_alumno)
                    ->select("Alumno.*", "c.Nombre as nombreCarrera", "e.Nombre as nombreEstado")
                    ->first();
        return $alumn;
    }
    public static function validateDown($AlumnoId) {

        try
          {
            $alumnoData = selectSicoes("Alumno","AlumnoId",$id_alumno)[0];
            if ($alumnoData["Baja"]==0)
            {
              return true;
            }
            else
            {
              return false;
            }
          }
          catch(\Exception $e)
          {
            return false;
          }
    }
	
	public static function constructAlumnArray($data) {

		$planEstudio = PlanEstudio::where("CarreraId", $data["Carrera"])
                        ->orderBy("PlanEstudioId", "desc")
                        ->first();
		//Edad, el plan de estudio
        $aux = abs(strtotime(date('Y-m-d')) - strtotime($data["FechaNacimiento"]));
        $edad = intval(floor($aux / (365*60*60*24)));
        $tempEnrollment = generateTempMatricula();

        $ApellidoSegundo = array_key_exists("ApellidoSegundo",$data) ? normalizeChars(strtoupper($data["ApellidoSegundo"])) : null;

		$array = array('Matricula' => $tempEnrollment,
                    'Nombre' => normalizeChars(strtoupper($data["Nombre"])),
                    'ApellidoPrimero'=> normalizeChars(strtoupper($data["ApellidoPrimero"])),
                    'ApellidoSegundo' => $ApellidoSegundo,
                    'Regular' => 1,
                    'Tipo' => 0,
                    'Curp' => array_key_exists("Curp",$data) ? strtoupper($data["Curp"]) : null,
                    'Genero' => $data["Genero"],
                    'FechaNacimiento' => $data["FechaNacimiento"],
                    'Edad' => $edad,
                    'MunicipioNac' => array_key_exists("MunicipioNac",$data) ? strtoupper($data["MunicipioNac"]) : null,
                    'EstadoNac' => array_key_exists("EstadoNac",$data)?strtoupper($data["EstadoNac"]):null,
                    'EdoCivil' => $data["EdoCivil"],
                    'Estatura' => 0,
                    'Peso' => 0,
                    'TipoSangre' => $data["TipoSangre"],
                    'Alergias' => array_key_exists("Alergias",$data)?strtoupper($data["Alergias"]):null,
                    'Padecimiento' => array_key_exists("Padecimiento",$data)?strtoupper($data["Padecimiento"]):null,
                    'ServicioMedico' => $data["ServicioMedico"],
                    'NumAfiliacion' => array_key_exists("NumAfiliacion",$data)?$data["NumAfiliacion"]:null,
                    'Domicilio' => array_key_exists("Domicilio",$data)?strtoupper($data["Domicilio"]):null,
                    'Colonia' => array_key_exists("Colonia",$data)?strtoupper($data["Colonia"]):null,
                    'Localidad' => array_key_exists("Localidad",$data)?strtoupper($data["Localidad"]):null,
                    'MunicipioDom'  => array_key_exists("MunicipioDom",$data)?$data["MunicipioDom"]:null,
                    'EstadoDom' => array_key_exists("EstadoDom",$data)?$data["EstadoDom"]:null,
                    'CodigoPostal' => array_key_exists("CodigoPostal",$data)?$data["CodigoPostal"]:null,
                    'Telefono' => array_key_exists("Telefono",$data)?$data["Telefono"]:null,
                    'Email' => array_key_exists("Email",$data)?$data["Email"]:null,
                    'EscuelaProcedenciaId' => array_key_exists("EscuelaProcedenciaId",$data)?$data["EscuelaProcedenciaId"]:null,
                    'AnioEgreso' => array_key_exists("AnioEgreso",$data)?$data["AnioEgreso"]:null,
                    'PromedioBachiller' => array_key_exists("PromedioBachiller",$data)?$data["PromedioBachiller"]:null,
                    'ContactoEmergencia'  =>  array_key_exists("ContactoEmergencia",$data)?strtoupper($data["ContactoEmergencia"]):null,
                    'ContactoDomicilio' => array_key_exists("ContactoDomicilio",$data)?strtoupper($data["ContactoDomicilio"]):null,
                    'ContactoTelefono' => array_key_exists("ContactoTelefono",$data)?strtoupper($data["ContactoTelefono"]):null,
                    'TutorNombre' =>  array_key_exists("TutorNombre",$data)?strtoupper($data["TutorNombre"]):null,
                    'TutorDomicilio' => array_key_exists("TutorDomicilio",$data)?strtoupper($data["TutorDomicilio"]):null,
                    'TutorTelefono' => array_key_exists("TutorTelefono",$data)?strtoupper($data["TutorTelefono"]):null,
                    'TutorOcupacion' => array_key_exists("TutorOcupacion",$data)?strtoupper($data["TutorOcupacion"]):null,
                    'TutorSueldoMensual' => array_key_exists("TutorSueldoMensual",$data)?$data["TutorSueldoMensual"]:null,
                    'MadreNombre' => array_key_exists("MadreNombre",$data)?strtoupper($data["MadreNombre"]):null,
                    'MadreDomicilio' => array_key_exists("MadreDomicilio",$data)?strtoupper($data["MadreDomicilio"]):null,
                    'MadreTelefono' => array_key_exists("MadreTelefono",$data)?strtoupper($data["MadreTelefono"]):null,
                    'TrabajaActualmente' => $data["TrabajaActualmente"],
                    'Puesto' => array_key_exists("Puesto",$data)?strtoupper($data["Puesto"]):null,
                    'SueldoMensualAlumno' => array_key_exists("SueldoMensualAlumno",$data)?$data["SueldoMensualAlumno"]:null,
                    'DeportePractica' => array_key_exists("DeportePractica",$data)?strtoupper($data["DeportePractica"]):null,
                    'Deportiva' => 0,
                    'Cultural' => 0,
                    'Academica' => 0,
                    'TransporteUniversidad' => array_key_exists("TransporteUniversidad",$data)?1:0,
                    'Transporte' => array_key_exists("Transporte",$data)?1:0,
                    'ActaNacimiento' => 0,
                    'CertificadoBachillerato' => 0,
                    'Baja' => 0,
                    'PlanEstudioId' => $planEstudio->PlanEstudioId,
                    'CirugiaMayor' => 0,
                    'CirugiaMenor' => 0,
                    'Hijo' => 0,
                    'Egresado' => 0
                );
		return $array;
	}
}