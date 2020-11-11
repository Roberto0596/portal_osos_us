<?php 

use App\Models\AdminUsers\AdminUser;
use App\Models\Alumns\Notify;
use App\Models\Alumns\DebitType;
use App\Models\Alumns\HighAverages;
use App\Models\PeriodModel;
use App\Models\ConfigModel;
use App\Models\Alumns\Document;
use App\Models\Alumns\DocumentType;
use App\Models\Alumns\User;
use App\Models\Alumns\Debit;
use App\Models\Alumns\FailedRegister;


//seccion del sistema

function getUnAdminDebitType() {
  $query = DebitType::where([["id","<>",1],["id","<>",5]])->get();
  return $query;
}

function validateDebitsWithOrderId($id_order, $status)
{
  $debits = Debit::where("id_order", $id_order)->get();
  foreach ($debits as $key => $value) {
    $value->status = $status;
    if ($value->has_file_id != null) {
      $document = Document::find($value->has_file_id);
      $document->payment = $status;
      $document->save();
    }
    $value->save();
  }
}

function getTotalDebitWithOtherConcept() {
  $debit = Debit::where([["id_alumno","=",current_user()->id_alumno],["debit_type_id", "<>", 1]])->get();
  return $debit->sum("amount");
}

function getOfficialDocuments() {
  return DocumentType::where("type", "=", 1)->get();
}

//agregar una nueva notificacion
function addNotify($text,$id,$route)
{
  $notify = new Notify();
  $notify->text = $text;
  $notify->alumn_id = $id;
  $notify->route = $route;
  $notify->save();
}

function getCurrentNotify() {
  $query = [["alumn_id","=",current_user()->id],["status","=","0"]];
  $notifys = Notify::where($query)->get();
  return $notifys;
}

//ver configuracion
function getConfig()
{
  if(session()->has('config-model')) {
    return session()->get('config-model');
  } else {
    $config = ConfigModel::first();
    return $config;
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

function selectUsersWithSicoes() {
  return DB::table("users")->where("id_alumno","<>",null)->get();
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
    $config = ConfigModel::all();
    if (count($config))
    {
      return PeriodModel::find($config[0]->period_id);
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
  $array =[
    ['description' => 'constancia de no adeudo', 'route' => 'alumn.constancia', 'PeriodoId' => $currentPeriod->id, 'alumn_id' => $id,'document_type_id' => 6],
    ['description' => 'cédula de reinscripción', 'route' => 'alumn.cedula', 'PeriodoId' => $currentPeriod->id, 'alumn_id' => $id,'document_type_id'=> 7]
  ];
  $insertDocument = insertIntoPortal("document",$array);
  return $insertDocument;
}

function insertInscriptionDebit(User $user)
{
  $message = ["type" => 0, "message" => "Termino la validación de tu información"];
  $debit_array = [
    'debit_type_id' => 1,
    'description' => 'Aportacion a la calidad estudiantil',
    'amount' => getConfig()->price_inscription,
    'admin_id'=> 2,
    'id_alumno' => $user->id_alumno,
    'status' => 0,
    'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
    'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
    'period_id' => getConfig()->period_id
  ];

  $validate = HighAverages::where([["enrollment","=",$user->getSicoesData()["Matricula"]],["periodo_id", "=",getConfig()->period_id]])->first();

  if($validate)
  {
    $debit_array["status"] = 1;
    $inscription = makeRegister($user);
    $message["message"] = "Felicidades por tu promedio, sigue así, no pagaras inscripción";
    $message["type"] = 1;
  } else {
    $user->inscripcion = 1;
    $user->save();
  }
  $create_debit = insertIntoPortal("debit",$debit_array);

  return $message;
}

function validateDocumentInscription($id_alumno, $document_type_id)
{
  $document = Document::where([["alumn_id","=",$id_alumno],["type","=",1],["document_type_id","=",$document_type_id]])->first();
  if (!$document || $document->status == 0) {
    return "card-danger|No se ha registrado el documento";
  } else if($document->status == 1){
    return "card-warning|Falta validación";
  } else {
    return "card-success|Todo esta en orden";
  }
}

function current_user($guard = null)
{
    return \Auth::guard($guard==null?"alumn":$guard)->user();
}

//seccion de sicoes
function ConectSqlDatabase()
{
  $password = env('SQL_SERVER_PASSWORD');
  $user = env('SQL_SERVER_USER');
  $database = env('SQL_SERVER_DATABASE');
	$link = new PDO("sqlsrv:Server=".env('SQL_SERVER_INSTANCE').";Database=".$database.";", $user, $password);
  $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $link;
}

//crear un nuevo registro en caso de que la inscripcion falle
function addFailedRegister($id,$message) {
  $instance = new FailedRegister();
  $instance->alumn_id = $id;
  $instance->period_id = getConfig()->period_id;
  $instance->message = $message;
  $instance->status = 0;
  $instance->save();
}


//funcion para inscribir al alumno
function makeRegister(User $user)
{
  $message = ["success" => [], "errors" => []];
  $inscripcionData = checkGroupData($user->getSicoesData());

  if ($inscripcionData == false) {
      $inscripcionData = ["Semestre" => 4, "EncGrupoId" => 1120];
      addFailedRegister($user->id, "no se encontro el grupo para este alumno.");
  }

  //entrara en la condicion cuando el alumno sea de nuevo ingreso
  if ($inscripcionData["Semestre"] == 1)
  {
    $enrollement = generateCarnet($user->getSicoesData()["PlanEstudioId"]);           
    updateByIdAlumn($user->id_alumno,"Matricula",$enrollement);
  } 

  $inscribir = inscribirAlumno([
    'Semestre' => $inscripcionData["Semestre"],
    'EncGrupoId'=> $inscripcionData["EncGrupoId"],
    'Fecha'=> getDateCustom(),
    'Baja' => 0, 
    'AlumnoId'=>$user->id_alumno
  ]);

  if ($inscribir) {
    $user->inscripcion=3;
    $user->save();
    addNotify("Pago de colegiatura",$user->id,"alumn.charge");
    insertInscriptionDocuments($user->id);
    array_push($message["success"], "proceso realizado con exito");
  } else {
    array_push($message["errors"], "No fue posible inscribir al alumno ".$user->name);
  }

  return $message;
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
function selectSicoes($table_name,$item = null,$value = null, $limit = 0)
{
    $query = "";
  	if ($item == null)
  	{
        $query = "SELECT * FROM $table_name";	
  	}
  	else
  	{
        $query = "SELECT * FROM $table_name where $item = '$value'";
  	}
    $stmt = ConectSqlDatabase()->prepare($query);
  	$stmt->execute();
  	return $stmt->fetchAll();
    $stmt->close();
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
    $password = env('SQL_SERVER_PASSWORD');
    $user = env('SQL_SERVER_USER');
    $database = env('SQL_SERVER_DATABASE');
    $link = new PDO("sqlsrv:Server=".env('SQL_SERVER_INSTANCE').";Database=".$database.";", $user, $password);
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
    $password = env('SQL_SERVER_PASSWORD');
    $user = env('SQL_SERVER_USER');
    $database = env('SQL_SERVER_DATABASE');
    $link = new PDO("sqlsrv:Server=".env('SQL_SERVER_INSTANCE').";Database=".$database.";", $user, $password);
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

function checkGroupData($alumnData)
{
  $odd = [1,3,5,7,9];
  $pair = [2,4,6,8];

  $inscripcionData = getInscription($alumnData["AlumnoId"]);
  $config = getConfig();
  $period = selectCurrentPeriod();
  $group = false;
  if ($inscripcionData!=false) {
    $nextSemester = $inscripcionData["Semestre"]+1;
    if ($period->semestre == 1) {
      if (in_array($nextSemester, $pair)) {
        $group = getGroupByPeriod($config->period_id, $alumnData["PlanEstudioId"], $nextSemester);
      } 
    } else {
      if (in_array($nextSemester, $odd)) {
        $group = getGroupByPeriod($config->period_id, $alumnData["PlanEstudioId"], $nextSemester);
      }
    }
  }
  else
  {
    $group = getGroupByPeriod($config->period_id,$alumnData["PlanEstudioId"],1);
  } 
  return $group ? $group : false; 
}

//trae el ultimo registro de la tabla de inscripcion, a excepción de los cursos de verano
function getInscription($id_alumno)
{
  $stmt = ConectSqlDatabase()->prepare("SELECT top(1)* from Inscripcion where AlumnoId = '$id_alumno' and Semestre <> 'E' order by InscripcionId desc;");
  $stmt->execute();
  return $stmt->fetch();
}

function getGroupAlumn($alumnData)
{
  $config = getConfig();
  $inscriptionData = getInscription($alumnData["AlumnoId"]);
  $group = getGroupByPeriod($config->period_id, $alumnData["PlanEstudioId"], ($inscriptionData["Semestre"]));
  return $group;
}

function getGroupByPeriod($periodo,$plan,$semestre)
{
  $config = getConfig();
  if ($plan == $config->lata_id && $semestre <= 3) {
    $stmt = ConectSqlDatabase()->prepare("SELECT * from EncGrupo where PeriodoId = '$periodo' and PlanEstudioId = '$config->laep_id' and Semestre = '$semestre';");
    $stmt->execute();
    return $stmt->fetch();
  } else {
    $stmt = ConectSqlDatabase()->prepare("SELECT * from EncGrupo where PeriodoId = '$periodo' and PlanEstudioId = '$plan' and Semestre = '$semestre';");
    $stmt->execute();
    return $stmt->fetch();
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


//metodo para traer un join con los datos del alumno
function getDataAlumnDebit($id_alumn)
{
  $stmt = ConectSqlDatabase()->prepare("SELECT a.Matricula, a.Nombre, a.ApellidoPrimero, a.Email, a.Localidad, a.ApellidoSegundo, c.Nombre as nombreCarrera, e.Nombre as nombreEstado from Alumno as a
                                      inner join PlanEstudio as p on p.PlanEstudioId = a.PlanEstudioId
                                      inner join Carrera as c on p.CarreraId = c.CarreraId
                                      inner join Estado as e on e.EstadoId = a.EstadoDom where AlumnoId = '$id_alumn'");
  $stmt->execute();
  return $stmt->fetch();
}


function createImage($photo, $pathCustom) {
  $rand = rand(100, 100000);
  $path = "";
  try {
      mkdir($pathCustom.$rand, 0755);
      $path = $pathCustom.$rand;
  } catch(\Exception $e) {
      $path = $pathCustom.$rand;
  }            
  $rand = rand(100, 100000);
  $documentName = $rand.".".$photo->getClientOriginalExtension();

  if (!file_exists($path."/".$documentName)) {
      $photo->move($path, $documentName);
  } else {
      unlink($path."/".$documentName);
      $photo->move($path, $documentName);
  }
  return $path."/".$documentName;
}

//validar con servicios escolares, que todos los alumnos del 2014 hacia atras esten en sicoes
function isNoob($id) {
  $user = User::find($id);
  if ($user->id_alumno == null) {
    return "/alumn/inscripcion";
  } else {
    return "/alumn/re-inscripcion";
  }
}

function getTotalWithComission($total, $tipo, $flag = true) {
  if ($tipo == "card") {
    $comission = (1 - (0.029 * 1.16));
    $comission_fixed = 2.5 * 1.16;
    $total_payment = ($total + $comission_fixed)/$comission;
    $total_comission = $total_payment - $total;
  } else if ($tipo == "oxxo") {
    $comission = (1 - (0.039 * 1.16));
    $total_payment = $total/$comission;
    $total_comission = $total_payment - $total;
  } else if ($tipo == "spei") {
    $comission = 12.5 * 1.16;
    $total_payment = $total + $comission;
    $total_comission = $total_payment - $total;
  }

  if ($flag) {
    return ceil($total_payment);
  } else {
    return ceil($total_comission);
  }
}

function getArrayItem($debits, $type) {
    $item_array = [];
    $total = $debits->sum("amount");
    foreach ($debits as $key => $value)
    {
        $items = array('name' => $value->debitType->concept,
                        "unit_price" => $value->amount*100,
                        "quantity" => 1);
        array_push($item_array, $items);
    }

    //agregamos la comision bancaria correspondiente.
    $commission = array('name' => 'comision bancaria',
                      'unit_price' => floatval((getTotalWithComission($total,$type,false)*100)),
                      'quantity'=>1);
    array_push($item_array, $commission);
    return $item_array;
}

function getDebitByArray($array) {
    $collection = collect();
    foreach ($array as $key => $value) {
       $debit = Debit::find($value["id"]);
       $collection->push($debit);
    }
    return $collection;
}

//metodo para traer el encGrupo
function getEncGrupoBySemestre($semester,$period)
{
    $stmt = ConectSqlDatabase()->prepare("SELECT * from EncGrupo where  Semestre = '$semester' and PeriodoId = '$period';");
    $stmt->execute();
    return $stmt->fetchAll();
    $stmt = null;
}

function addLog($message) {
  $path = public_path()."/log.txt";
  $data = json_decode(file_get_contents($path),true);
  array_push($data["errors"], ["mensaje" => $message, "fecha" => getDateCustom()]);
  file_put_contents($path, json_encode($data));
}