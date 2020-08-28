<?php 

use App\Models\AdminUsers\AdminUser;
use App\Models\Alumns\Notify;
use App\Models\Alumns\DebitType;
use App\Models\Alumns\HighAverages;
use App\Models\PeriodModel;
use App\Models\ConfigModel;

//seccion del sistema

//agregar una nueva notificacion
function addNotify($text,$id,$route)
{
  $notify = new Notify();
  $notify->text = $text;
  $notify->alumn_id = $id;
  $notify->route = $route;
  $notify->save();
}

//ver configuracion
function getConfig()
{
  $config = ConfigModel::find(1);
  return $config;
}

//validar en la tabla de promedios altos
function validateHighAverage($enrollement)
{
    $validate = HighAverages::where("enrollment","=",$enrollement)->get();
    if(count($validate)!=0)
    {
      return true;
    }
    else
    {
      return false;
    }
}

function getDebitType($id = null)
{
  if ($id == null)
  {
    return DebitType::all();
  }
  else
  {
    return DebitType::find($id);
  }
}

function selectTable($tableName, $item=null,$value=null,$limit=null)
{
  if ($item == null)
  {
    return DB::table($tableName)->get();
  }
  else
  {
    if ($limit==null)
    {
      return DB::table($tableName)->where($item,"=",$value)->get();
    }
    else
    {
      return DB::table($tableName)->where($item,"=",$value)->first();
    }
  }
}

//este metodo servira para trarnos el periodo actual o en curso
function selectCurrentPeriod()
{
    $currentPeriod = PeriodModel::all();
    if (count($currentPeriod))
    {
      return $currentPeriod[0];
    }
    else
    {
      return "error";
    }
}

function insertIntoPortal($tableName,$array)
{
  try
  {
    $insertar = DB::table($tableName)->insert($array);
    return true;
  }
  catch(\Exception $e)
  {
    return false;
  }
}

function insertInscriptionDocuments($id)
{
  $currentPeriod = selectCurrentPeriod();
  $array =[['name' => 'constancia de no adeudo', 'route' => 'alumn.constancia', 'PeriodoId' => $currentPeriod->id, 'alumn_id' => $id],['name' => 'cédula de reinscripción', 'route' => 'alumn.cedula', 'PeriodoId' => $currentPeriod->id, 'alumn_id' => $id]
        ];
  $insertDocument = insertIntoPortal("document",$array);
  return $insertDocument;
}

function insertInscriptionDebit($id_alumno)
{
  $data = selectSicoes("Alumno","AlumnoId",$id_alumno)[0];
  $validate = validateHighAverage($data["Matricula"]);
  if($validate)
  {
    $inscription = realizarInscripcion($id_alumno);
    if($inscription!=false)
    {
      return true;
    }
    else
    {
      return false;
    }
  }
  else
  {
    $mytime = \Carbon\Carbon::now();
    DB::table('debit')->insert(
        ['debit_type_id' => 1,
         'description' => 'Pago semestral de inscripcion',
         'amount' => 1950.00,
         'admin_id'=> 2,
         'id_alumno'=>$id_alumno,
         'created_at'=>$mytime->toDateTimeString(),
         'updated_at'=>$mytime->toDateTimeString()]
    );
    return 0;
  }
}

//seccion de sicoes
function ConectSqlDatabase()
{
  $password = "admin123";
  $user = "robert";
  $rutaServidor = "127.0.0.1";
	$link = new PDO("sqlsrv:Server=.\SQLEXPRESS01;Database=Sicoes;", $user, $password);
  $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $link;
}

//funcion para inscribir al alumno
function realizarInscripcion($id_alumno)
{
   $inscripcionData = getLastThing("Inscripcion","AlumnoId",$id_alumno,"InscripcionId");
  //verificamos que es un alumno nuevo y no se esta inscribiendo
  if (!$inscripcionData)
  {
      //traemos la matricula para el alumno que acaba de pagar
      $sicoesAlumn = selectSicoes("Alumno","AlumnoId",$id_alumno)[0];
      $enrollement = generateCarnet($sicoesAlumn["PlanEstudioId"]);           
      $semester = 1;
  } 
  else
  {
      $semester = $inscripcionData["Semestre"]+1;
  }
  $validateStatus = validateStatusAlumn($id_alumno);

  //inscribimos al alumno despues de pagar
  $inscribir = inscribirAlumno(['Semestre' => $semester,'EncGrupoId'=> $validateStatus["EncGrupoId"],'Fecha'=> getDateCustom(),'Baja'=>0, 'AlumnoId'=>$id_alumno]);

  if ($inscribir)
  {
    return !$inscripcionData?$enrollement:"reinscripcion";
  }
  else
  {
      return false;
  }
}

//metodo auxiliar para saber las ultimas cargas del alumno
function selectLastCharge($AlumnoId)
{
    $stmt = ConectSqlDatabase()->prepare("SELECT top(1) * FROM Carga where AlumnoId = :AlumnoId order by CargaId desc");
    $stmt->bindParam(":AlumnoId",$AlumnoId,PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch();
    $stmt = null;
}

//metodo que nos da el ultimo semestre en el que estuvo el alumno, de manera que, el resultado de este metodo se le subara 1.
function getLastSemester($alumnId)
{
    $lastSemester = getLastThing("Inscripcion","AlumnoId",$alumnId,"InscripcionId");
    if (!$lastSemester)
    {
      return 1;
    }
    else
    {
      return $lastSemester["Semestre"];
    }
}

//metodo para traernos un array con las materias que el alumno puede llevar
function getCurrentAsignatures($alumnId)
{
    $alumnData = selectSicoes("alumno","AlumnoId",$alumnId)[0];
    $inscipcion = getLastThing("Inscripcion","AlumnoId",$alumnId,"InscripcionId");
    return getAsignatures($inscipcion["Semestre"],$alumnData["PlanEstudioId"]);
}

//metodo que nos trae todas las asignaturas
function getAsignatures($semester,$planid)
{
    $stmt = ConectSqlDatabase()->prepare("SELECT * FROM Asignatura where Semestre = :semestre and PlanEstudioId = :PlanEstudioId");
    $stmt->bindParam(":semestre",$semester,PDO::PARAM_STR);
    $stmt->bindParam(":PlanEstudioId",$planid,PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll();
    $stmt = null;
}

function getDetGrupo($AsignaturaId)
{
    $stmt = ConectSqlDatabase()->prepare("SELECT top(1) * FROM DetGrupo where AsignaturaId = :AsignaturaId order by DetGrupoId desc");
    $stmt->bindParam(":AsignaturaId",$AsignaturaId,PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch();
    $stmt = null;
}

function getLastThing($table_name,$item,$value,$orderby)
{
    $stmt = ConectSqlDatabase()->prepare("SELECT top(1) * FROM $table_name where $item = :$item order by $orderby desc");
    $stmt->bindParam(":".$item,$value,PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch();
    $stmt = null;
}

//metodo default para hacer consultas a la base de datos de sicoes
function selectSicoes($table_name,$item = null,$value = null,$limit = 0)
{
	if ($item == null)
	{
		if ($limit==0)
		{
			$stmt = ConectSqlDatabase()->prepare("SELECT * FROM $table_name");
		}
		else
		{
			$stmt = ConectSqlDatabase()->prepare("SELECT top(:bol)* FROM $table_name");
			$stmt->bindParam(":bol",$limit,PDO::PARAM_INT);
		}		
	}
	else
	{
		$stmt = ConectSqlDatabase()->prepare("SELECT * FROM $table_name where $item = :$item");
		$stmt->bindParam(":".$item,$value,PDO::PARAM_STR);
	}
	$stmt->execute();
	return $stmt->fetchAll();
	$stmt = null;
}

//funcion para borrar los registros de la carga en caso de que alguna falle.
function deleteCharge($array)
{
    $validator = [];
    foreach ($array as $key => $value)
    {
        $stmt = ConectSqlDatabase()->prepare("DELETE FROM Carga where CargaId = :CargaId");
        $stmt->bindParam(":CargaId",$value, PDO::PARAM_INT);
        if ($stmt->execute()) 
        {
            array_push($validator, true);
        }
        else
        {
            array_push($validator, false);
        }
    }
    return $validator;
}

function insertCharge($array)
{
    $password = "admin123";
    $user = "robert";
    $rutaServidor = "127.0.0.1";
    $link = new PDO("sqlsrv:Server=DESKTOP-UP7PDGG\SQLEXPRESS01;Database=Sicoes;", $user, $password);
    $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $link->prepare("INSERT INTO Carga(Baja,AlumnoId,DetGrupoId,PeriodoId) values(:Baja,:AlumnoId,:DetGrupoId,:PeriodoId)");
    $array = array('Baja' => $array["Baja"],
                    'AlumnoId'=>$array["AlumnoId"],
                    'DetGrupoId'=>$array["DetGrupoId"],
                    'PeriodoId'=>$array["PeriodoId"]);
    if($stmt->execute($array))
    {
        return $link->lastInsertId();
    }
    else
    {
        return false;
    }
    $stmt = null;
}

function inscribirAlumno($array)
{
  $stmt = ConectSqlDatabase()->prepare("INSERT INTO Inscripcion(Semestre,EncGrupoId,Fecha,Baja,AlumnoId) values(:Semestre,:EncGrupoId,:Fecha,:Baja,:AlumnoId)");

  if($stmt->execute($array))
  {
      return true;
  }
  else
  {
      return false;
  }
  $stmt = null;
}

function InsertAlumn($array)
{
    $password = "admin123";
    $user = "robert";
    $rutaServidor = "127.0.0.1";
    $link = new PDO("sqlsrv:Server=DESKTOP-UP7PDGG\SQLEXPRESS01;Database=Sicoes;", $user, $password);
    $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $link->prepare("INSERT INTO Alumno(
           Matricula,
           Nombre,
           ApellidoPrimero,
           ApellidoSegundo,
           Regular,
           Tipo,
           Curp,
           Genero,
           FechaNacimiento,
           Edad,
           MunicipioNac,
           EstadoNac,
           EdoCivil,
           Estatura,
           Peso,
           TipoSangre,
           Alergias,
           Padecimiento,
           ServicioMedico,
           NumAfiliacion,
           Domicilio,
           Colonia,
           Localidad,
           MunicipioDom,
           EstadoDom,
           CodigoPostal,
           Telefono,
           Email,
           EscuelaProcedenciaId,
           AnioEgreso,
           PromedioBachiller,
           ContactoEmergencia,
           ContactoDomicilio,
           ContactoTelefono,
           TutorNombre,
           TutorDomicilio,
           TutorTelefono,
           TutorOcupacion,
           TutorSueldoMensual,
           MadreNombre,
           MadreDomicilio,
           MadreTelefono,
           TrabajaActualmente,
           Puesto,
           SueldoMensualAlumno,
           DeportePractica,
           Deportiva,
           Cultural,
           Academica,
           TransporteUniversidad,
           Transporte,
           ActaNacimiento,
           CertificadoBachillerato,
           Baja,
           PlanEstudioId,
           CirugiaMayor,
           CirugiaMenor,
           Hijo,
           Egresado) 
    VALUES(:Matricula,
           :Nombre,
           :ApellidoPrimero,
           :ApellidoSegundo,
           :Regular,
           :Tipo,
           :Curp,
           :Genero,
           :FechaNacimiento,
           :Edad,
           :MunicipioNac,
           :EstadoNac,
           :EdoCivil,
           :Estatura,
           :Peso,
           :TipoSangre,
           :Alergias,
           :Padecimiento,
           :ServicioMedico,
           :NumAfiliacion,
           :Domicilio,
           :Colonia,
           :Localidad,
           :MunicipioDom,
           :EstadoDom,
           :CodigoPostal,
           :Telefono,
           :Email,
           :EscuelaProcedenciaId,
           :AnioEgreso,
           :PromedioBachiller,
           :ContactoEmergencia,
           :ContactoDomicilio,
           :ContactoTelefono,
           :TutorNombre,
           :TutorDomicilio,
           :TutorTelefono,
           :TutorOcupacion,
           :TutorSueldoMensual,
           :MadreNombre,
           :MadreDomicilio,
           :MadreTelefono,
           :TrabajaActualmente,
           :Puesto,
           :SueldoMensualAlumno,
           :DeportePractica,
           :Deportiva,
           :Cultural,
           :Academica,
           :TransporteUniversidad,
           :Transporte,
           :ActaNacimiento,
           :CertificadoBachillerato,
           :Baja,
           :PlanEstudioId,
           :CirugiaMayor,
           :CirugiaMenor,
           :Hijo,
           :Egresado)");

    // dd($array);

    if($stmt->execute($array))
    {
        return $link->lastInsertId();
    }
    else
    {
        return false;
    }
    $stmt = null;
}

//aplica para tablas que tiene un campo nombre 

function getItemClaveAndNamesFromTables($table_name)
{
    $stmt = ConectSqlDatabase()->prepare("SELECT Clave,Nombre FROM $table_name");
    $stmt->execute();
	return $stmt->fetchAll();

}

function getCarreras()
{
    $stmt = ConectSqlDatabase()->prepare("SELECT Carreraid,Nombre FROM Carrera");
    $stmt->execute();
	return $stmt->fetchAll();

}

function updateByIdAlumn($id_alumn,$colName,$value)
{
    $sql = "UPDATE Alumno SET $colName = :colvalue where AlumnoId = :alumnoid";
    $datos = array("colvalue"=> $value, "alumnoid"=> $id_alumn);
    $stmt= ConectSqlDatabase()->prepare($sql);
    $stmt->execute($datos);
}   

function getEstadoMunicipio($matricula, $desicion){

    if ($desicion == 1){
        $stmt = ConectSqlDatabase()->prepare("SELECT m.nombre as municipio, e.nombre as estado from Municipio as m join Estado as e on e.EstadoId = m.EstadoId 
        where MunicipioId = (select municipionac from alumno where matricula = '$matricula')");
        $stmt->execute();
        $localidad = $stmt->fetchAll();

        if (count($localidad) > 0){
            return $localidad[0];
        }
    }
    else{
        $stmt = ConectSqlDatabase()->prepare("SELECT m.Nombre as municipio,
         e.Nombre as estado from Alumno as a
        join Municipio as m on m.MunicipioId = a.MunicipioDom join Estado as e on e.EstadoId = m.EstadoId where matricula = '$matricula'");
        $stmt->execute();
        $localidad = $stmt->fetchAll();

        if (count($localidad) > 0){
            return $localidad[0];
        }
    }
    return 'VACIO';
    $stmt = null;
}

function getCarrera($matricula){
    $stmt = ConectSqlDatabase()->prepare("SELECT c.nombre as carrera, p.Nombre as planDeEstudio from Alumno as a 
    join PlanEstudio as p on p.PlanEstudioId = a.PlanEstudioId
    join Carrera as c on c.CarreraId = p.CarreraId where a.Matricula = '$matricula'");
    $stmt->execute();
    $carrera = $stmt->fetchAll();

    return $carrera[0];
    $stmt = null;
}

function getAlumnoId($matricula){
    $stmt = ConectSqlDatabase()->prepare("SELECT AlumnoId FROM alumno where matricula = '$matricula'");
    $stmt->execute();
    $alumno = $stmt->fetchAll();

    return $alumno[0];
    $stmt = null;
}

function lastEnrollement($planEstudioId,$clave,$fecha)
{
    $like = $fecha."-".$clave."-%%%%";
    $stmt = ConectSqlDatabase()->prepare("SELECT Matricula FROM Alumno where PlanEstudioId = '$planEstudioId' and Matricula like '$like' order by AlumnoId desc");
    $stmt->execute();
    $alumno = $stmt->fetch();
    return $alumno;
    $stmt = null;
}

function generateCarnet($planEstudioId)
{
  $plan = selectSicoes("PlanEstudio","PlanEstudioId",$planEstudioId)[0];
  $date = getDate();
  $year = substr($date["year"], -2);
  $clave = selectSicoes("Carrera","CarreraId",$plan["CarreraId"])[0];
  $lastAlumn = lastEnrollement($planEstudioId,$clave["Clave"],$year);
  if (!$lastAlumn)
  {
    return $year."-".$clave["Clave"]."-0001";
  }
  else
  {
    $sum = substr($lastAlumn["Matricula"],-4) + 1;
    if (strlen($sum)==1)
      $lastDate = "000".$sum;
    else if (strlen($sum)==2) 
      $lastDate = "00".$sum;
    else 
      $lastDate = "0".$sum;

    $matricula = $year."-".$clave["Clave"]."-".$lastDate;
    return $matricula;
  }
}

function getEncGrupo()
{
  $stmt = ConectSqlDatabase()->prepare("SELECT Nombre FROM EncGrupo");
  $stmt->execute();
  $nombre = $stmt->fetchAll();
  return $nombre;
  $stmt = null;
}

function agruparPorSalon($EncGrupoId)
{
  $stmt = ConectSqlDatabase()->prepare("SELECT a.Nombre, a.Matricula, e.Nombre as Grupo from Alumno as a inner join Inscripcion as i on a.AlumnoId = i.AlumnoId inner join EncGrupo as e on i.EncGrupoId = e.EncGrupoId where e.EncGrupoId = :EncGrupoId;");
  $stmt->bindParam(":EncGrupoId", $EncGrupoId, PDO::PARAM_STR);
  $stmt->execute();
  $nombre = $stmt->fetchAll();
  return $nombre;
  $stmt = null;
  
}

function getInscriptionData($AlumnoId)
{
    $stmt = ConectSqlDatabase()->prepare("SELECT TOP(1) InscripcionId,Semestre,EncGrupoId from Inscripcion as i inner join Alumno as a on i.AlumnoId = a.AlumnoId where a.AlumnoId = :AlumnoId and i.Baja = 0 and a.Baja = 0 order by i.InscripcionId desc;");
    $stmt->bindParam("AlumnoId",$AlumnoId,PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch();
    $stmt = null;
}

function getGroups($table, $field)
{
  $link = new \PDO("mysql:host=localhost;dbname=portal","root","");
  $link->exec("set names utf8");
  $stmt = $link->prepare("SELECT count($field) as total, $field FROM $table GROUP by $field");
  $stmt->execute();
  return $stmt->fetchAll();
}

function getDateCustom()
{
  date_default_timezone_set('America/Hermosillo');
  $date = date('Y-m-d');
  $hour = date('H:i:s');
  return $date.'T'.$hour;
}

function obtenerGrupo($semestre,$planEstudioId,$periodoId)
{
  $stmt = ConectSqlDatabase()->prepare("SELECT top(1) * FROM EncGrupo where Semestre = '$semestre' and PlanEstudioId = '$planEstudioId' and PeriodoId = '$periodoId' order by EncGrupoId");
  $stmt->execute();
  return $stmt->fetch();
  $stmt = null;
}

function getActiveCarrer()
{
  $stmt = ConectSqlDatabase()->prepare("SELECT * from Carrera where CarreraId <> 8 and CarreraId <> 4 and CarreraId <> 7;");
  $stmt->execute();
  return $stmt->fetchAll();
  $stmt = null;
}

function getLastPeriod()
{
  $stmt = ConectSqlDatabase()->prepare("SELECT top(2)* from Periodo where Semestre <> 'CURSO DE VERANO' order by PeriodoId desc;");
  $stmt->execute();
  return $stmt->fetchAll();
  $stmt = null;
}

function getAlumnLastPeriod()
{
  $lastPeriod = getLastPeriod();
  $stmt = ConectSqlDatabase()->prepare("SELECT a.Matricula, a.PlanEstudioId, e.EncGrupoId from Alumno as a inner join Inscripcion as i on a.AlumnoId = i.AlumnoId
  inner Join EncGrupo as e on i.EncGrupoId = e.EncGrupoId where e.PeriodoId = :PeriodoId order by a.Matricula desc");
  $stmt->bindParam(":PeriodoId",$lastPeriod[1]["PeriodoId"],PDO::PARAM_INT);
  $stmt->execute();
  return $stmt->fetchAll();
  $stmt = null;
}

function getAlumnGroup($id_alumno)
{
  $data = selectSicoes("Alumno","AlumnoId",$id_alumno)[0]; 
  $inscripcion = getLastThing("Inscripcion","AlumnoId",$id_alumno,"InscripcionId");
  $currentPeriod = selectCurrentPeriod();
  if ($inscripcion!=false)
  {
    $group =  obtenerGrupo(($inscripcion["Semestre"]+1),$data["PlanEstudioId"],$currentPeriod->id);
  }
  else
  {
    $group =  obtenerGrupo(1,$data["PlanEstudioId"],$currentPeriod->id);
  }
  return $group;
}

//auxiliari methods
function generatePasssword()
{
    $letters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $numbers = '1234567890';
    $password = '';
    for($i = 0; $i < 4; $i++){
        $randomIndexLetras = mt_rand(0,strlen($letters) - 1);
        $randomIndexNumbers = mt_rand(0,strlen($numbers) - 1);
        $password = $password.$letters[$randomIndexLetras].$numbers[$randomIndexNumbers];  
    }
    return $password;
}

//metodos para generar la matricula
function getMatriculaTemp()
{
  $stmt = ConectSqlDatabase()->prepare("SELECT Matricula FROM Alumno where Matricula like 'Aspirante%' order by AlumnoId desc");
  $stmt->execute();
  $alumno = $stmt->fetch();
  return $alumno;
  $stmt = null;
}

function generateTempMatricula()
{
  $temp = getMatriculaTemp();
  if ($temp!=false)
  {
    $tempNumber = substr($temp["Matricula"],9)+1;
    return "Aspirante".$tempNumber;
  }
  else
  {
    return "Aspirante1";
  }
}

//verificar si un alumno reprobo una materia
function validateStatusAlumn($id_alumno)
{
  $inscripcionData = getInscription($id_alumno);
  $alumnoData = selectSicoes("Alumno","AlumnoId",$id_alumno)[0];
  $periodoData = selectCurrentPeriod();

  if ($inscripcionData) 
  {
    $encGrupoData = selectSicoes("EncGrupo","EncGrupoId",$inscripcionData["EncGrupoId"]);
    $periodo = $encGrupoData[0]["PeriodoId"];
    $charge = getChargeByPeriod($periodo,$id_alumno);
    $prom = calculateProm($charge);
    if ($prom < 4)
    {
      if($inscripcionData["Semestre"]<9)
      {
        $group = getGroupByPeriod($periodoData->id,$alumnoData["PlanEstudioId"],($inscripcionData["Semestre"]+1));
      }
      else
      {
        $group = getGroupByPeriod($periodoData->id,$alumnoData["PlanEstudioId"],($inscripcionData["Semestre"]));
      }
      if($group!=false)
      { 
        return $group;
      }      
      else
      {
        $group = getLastThing("EncGrupo","PlanEstudioId",$alumnoData["PlanEstudioId"],"EncGrupoId");
        return $group;
      }
    }
    else
    {
      $group = getGroupByPeriod($periodo,$alumnoData["PlanEstudioId"],($inscripcionData["Semestre"]));
      if ($group["Semestre"] != $inscripcionData["Semestre"])
      {
        return $group;
      }
      else
      {
        $group = getGroupByPeriod($periodoData->id,$alumnoData["PlanEstudioId"],($inscripcionData["Semestre"]));
        return $group;
      }
    }
  }
  else
  {
    if ($alumnoData["PlanEstudioId"]==11)
    {
      $group = getGroupByPeriod($periodoData->id,7,1);
      return $group;
    }
    else
    {
      $group = getGroupByPeriod($periodoData->id,$alumnoData["PlanEstudioId"],1);
      return $group;
    }
  }
}

function getInscription($id_alumno)
{
  $stmt = ConectSqlDatabase()->prepare("SELECT top(1)* from Inscripcion where AlumnoId = '$id_alumno' and Semestre <> 'E' order by InscripcionId desc;");
  $stmt->execute();
  return $stmt->fetch();
}

function getGroupByPeriod($periodo,$plan,$semestre)
{
  $stmt = ConectSqlDatabase()->prepare("SELECT * from EncGrupo where PeriodoId = '$periodo' and PlanEstudioId = '$plan' and Semestre = '$semestre';");
  $stmt->execute();
  return $stmt->fetch();
}

//metodo que nos trae la carga del alumno
function getChargeByPeriod($period,$id_alumno)
{
  $stmt = ConectSqlDatabase()->prepare("SELECT * from Carga where PeriodoId = '$period' and AlumnoId = '$id_alumno';");
  $stmt->execute();
  return $stmt->fetchAll();
  $stmt = null;
}

//metodo para calcular el promedio
function calculateProm($array)
{
  $count=0;
  foreach ($array as $key => $value)
  {
    if ($value["Calificacion"] < 70){
      $count++;
    }
  }
  return $count;
}

// function calculateProm($array)
// {
//   $prom=0;
//   foreach ($array as $key => $value)
//   {
//     $prom = $prom + $value["Calificacion"];
//   }
//   $prom = $prom/count($array);
//   return $prom;
// }

//validar si no es un alumno en baja 
function validateDown($id_alumno)
{
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

//actualizar individual

function updateSicoes($table, $field, $value, $item, $valueItem)
{
    $sql = "UPDATE $table SET $field = '$value' where $item = '$valueItem'";
    $stmt= ConectSqlDatabase()->prepare($sql);
    if($stmt->execute())
    {
      return "success";
    }
    else
    {
      return "error";
    }
}

//otros
function ctrCrearImagen($foto,$id,$folder,$nuevoAncho,$nuevoAlto,$flag)
{
    $ruta;
    list($ancho,$alto) = getimagesize($foto["tmp_name"]);
    if($flag==false)
    {
        mkdir("img/".$folder."/".$id,0755);
    }  
    if ($foto["type"] == "image/jpeg")
    {
        $aleatorio = mt_rand(100,999);
        $ruta = "img/".$folder."/".$id."/".$aleatorio.".jpg";
        $origen = imagecreatefromjpeg($foto["tmp_name"]);
        $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
        imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
        imagejpeg($destino,$ruta);
    }
    if ($foto["type"] == "image/png")
    {
        $aleatorio = mt_rand(100,999);
        $ruta = "img/".$folder."/".$id."/".$aleatorio.".png";
        $origen = imagecreatefrompng($foto["tmp_name"]);
        $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
        imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
        imagepng($destino,$ruta);
    }
    return $ruta;
}