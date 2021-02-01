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
use App\Models\Alumns\Ticket;
use Carbon\Carbon;
use Mpdf\Mpdf;

//seccion del sistema

//optiene el tipo de adeudo sin contar el de inscripcion
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
    try {
        createTicket($value->id);
    } catch(\Exception $e){
    }
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
  $config = ConfigModel::first();
  return $config;
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
    $config = ConfigModel::first();
    return PeriodModel::find($config->period_id);
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
  $alumnData = $user->getSicoesData();
  $debit_array = [
    'debit_type_id' => 1,
    'description' => 'Aportacion a la calidad estudiantil',
    'amount' => getConfig()->price_inscription,
    'admin_id'=> 2,
    'id_alumno' => $user->id_alumno,
    'status' => 0,
    'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
    'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
    'period_id' => getConfig()->period_id,
    'enrollment' => $alumnData["Matricula"],
    'alumn_name' => $alumnData["Nombre"],
    'alumn_last_name' => $alumnData["ApellidoPrimero"],
    'alumn_second_last_name' => (isset($alumnData["ApellidoSegundo"]) ? $alumnData["ApellidoSegundo"] : '')
  ];

  $validate = HighAverages::where("enrollment",$user->getSicoesData()["Matricula"])->where("status", 0)->first();

  if($validate)
  {
    $debit_array["status"] = 1;
    $inscription = makeRegister($user);
    $message["message"] = "Felicidades por tu promedio, sigue así, no pagaras inscripción";
    $message["type"] = 1;
    $validate->status = 1;
    $validate->save();
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

//crear un nuevo registro en caso de que la inscripcion falle
function addFailedRegister($id,$message) {
  $instance = new FailedRegister();
  $instance->alumn_id = $id;
  $instance->period_id = getConfig()->period_id;
  $instance->message = $message;
  $instance->status = 0;
  $instance->save();
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
    $user->email = "a".str_replace("-", "", $enrollement)."@unisierra.edu.mx";
  } 

  $inscribir = inscribirAlumno([
    'Semestre' => $inscripcionData["Semestre"],
    'EncGrupoId'=> $inscripcionData["EncGrupoId"],
    'Fecha'=> getDateCustom(),
    'Baja' => 0, 
    'AlumnoId'=>$user->id_alumno,
    'PeriodoId' => getConfig()->period_id,
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

function inscribirAlumno($array)
{
  $stmt = ConectSqlDatabase()->prepare("INSERT INTO Inscripcion(Semestre,EncGrupoId,Fecha,Baja,AlumnoId, PeriodoId) values(:Semestre,:EncGrupoId,:Fecha,:Baja,:AlumnoId,:PeriodoId)");

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

function current_group($id_alumno) {
    $stmt = ConectSqlDatabase()->prepare("SELECT top(1)*  from Inscripcion
    inner join EncGrupo on Inscripcion.EncGrupoId = EncGrupo.EncGrupoId
    where AlumnoId = '$id_alumno' order by InscripcionId desc");
    $stmt->execute();
    return $stmt->fetch();
    $stmt = null;
}

	/*
	|-------------------------------------------------------------------
	| Metodo para generar un Ticket
	|-------------------------------------------------------------------
	*/
 function createTicket($debit_id)
  {

    $debit = Debit::find($debit_id);
    $alumn = User::where("id_alumno", $debit->id_alumno)->first();
    $date =  Carbon::now()->toDateTimeString();

    
    $html = view('Alumn.ticket.template',['alumn'=>$alumn,'debit'=>$debit,'date'=>$date])->render();
    $namefile = ucwords($debit->description).time().'.pdf';
    $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
    $fontDirs = $defaultConfig['fontDir'];
    $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
    $fontData = $defaultFontConfig['fontdata'];
    $mpdf = new Mpdf([
        'fontDir' => array_merge($fontDirs, [
            public_path() . '/fonts',
        ]),
        'fontdata' => $fontData + [
            'arial' => [
                'R' => 'arial.ttf',
                'B' => 'arialbd.ttf',
            ],
        ],
        'default_font' => 'arial',
        "format" => [210,297],
    ]);
   
    $mpdf->SetDisplayMode('fullpage');
    $mpdf->WriteHTML($html);

    $alumnData = $alumn->getSicoesData();
    $path = "tickets/".$alumnData["Matricula"];


    if (! is_dir(public_path()."/".$path)) {
        mkdir(public_path()."/".$path, 0777, true);
    }

    try {     
      $mpdf->Output($path."/".$namefile,"F");
    } catch(\Exception $e) {
      $mpdf->Output(public_path()."/". $path."/".$namefile,"F");
    }

    $ticket = new Ticket();
    $ticket->concept = ucwords($debit->description);
    $ticket->alumn_id = $alumn->id;
    $ticket->debit_id = $debit->id;
    $ticket->route = $path."/".$namefile;
    $ticket->created_at = time();
    $ticket->save();  
}


function getAlumnByEnrollment($enrollment)
{
  $value = $enrollment."%";
  $stmt = ConectSqlDatabase()->prepare(
    "SELECT AlumnoId,Matricula,Nombre,ApellidoPrimero,ApellidoSegundo
    FROM Alumno where Matricula LIKE '$value'");
  $stmt->execute();
  return $stmt->fetchAll();
  $stmt = null;
}

//metodo para saber los periodos que ah cursado o lleva un alumno
function getAlumnPeriods($alumn_id)
{
  $stmt = ConectSqlDatabase()->prepare(
    "SELECT * FROM Periodo WHERE PeriodoId IN
    (SELECT  DISTINCT(PeriodoId) FROM Carga where AlumnoId = $alumn_id)");
  $stmt->execute();
  return $stmt->fetchAll();
  $stmt = null;
}

function getAcademicChargeByPeriodIdAndAlumnId($period_id,$alumn_id)
{
  $stmt = ConectSqlDatabase()->prepare(
    "SELECT c.CargaId,c.Calificacion,c.Baja,c.PeriodoId,
    det.ProfesorId, det.AsignaturaId,
    a.Nombre AS Asignatura,a.Semestre,
    prof.Nombre,prof.ApellidoPrimero,prof.ApellidoSegundo
    FROM Carga AS c
    INNER jOIN DetGrupo AS det ON c.DetGrupoId = det.DetGrupoId
    INNER JOIN Asignatura AS a ON det.AsignaturaId = a.AsignaturaId
    INNER JOIN Profesor AS prof ON det.ProfesorId = prof.ProfesorId
    WHERE AlumnoId = $alumn_id AND PeriodoId = $period_id"
  );
  $stmt->execute();
  return $stmt->fetchAll();
  $stmt = null;
}

function normalizeChars($s) {
    $replace = array(
        'ъ'=>'-', 'Ь'=>'-', 'Ъ'=>'-', 'ь'=>'-',
        'Ă'=>'A', 'Ą'=>'A', 'À'=>'A', 'Ã'=>'A', 'Á'=>'A', 'Æ'=>'A', 'Â'=>'A', 'Å'=>'A', 'Ä'=>'Ae',
        'Þ'=>'B',
        'Ć'=>'C', 'ץ'=>'C', 'Ç'=>'C',
        'È'=>'E', 'Ę'=>'E', 'É'=>'E', 'Ë'=>'E', 'Ê'=>'E',
        'Ğ'=>'G',
        'İ'=>'I', 'Ï'=>'I', 'Î'=>'I', 'Í'=>'I', 'Ì'=>'I',
        'Ł'=>'L',
        'Ñ'=>'N', 'Ń'=>'N',
        'Ø'=>'O', 'Ó'=>'O', 'Ò'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'Oe',
        'Ş'=>'S', 'Ś'=>'S', 'Ș'=>'S', 'Š'=>'S',
        'Ț'=>'T',
        'Ù'=>'U', 'Û'=>'U', 'Ú'=>'U', 'Ü'=>'Ue',
        'Ý'=>'Y',
        'Ź'=>'Z', 'Ž'=>'Z', 'Ż'=>'Z',
        'â'=>'a', 'ǎ'=>'a', 'ą'=>'a', 'á'=>'a', 'ă'=>'a', 'ã'=>'a', 'Ǎ'=>'a', 'а'=>'a', 'А'=>'a', 'å'=>'a', 'à'=>'a', 'א'=>'a', 'Ǻ'=>'a', 'Ā'=>'a', 'ǻ'=>'a', 'ā'=>'a', 'ä'=>'ae', 'æ'=>'ae', 'Ǽ'=>'ae', 'ǽ'=>'ae',
        'б'=>'b', 'ב'=>'b', 'Б'=>'b', 'þ'=>'b',
        'ĉ'=>'c', 'Ĉ'=>'c', 'Ċ'=>'c', 'ć'=>'c', 'ç'=>'c', 'ц'=>'c', 'צ'=>'c', 'ċ'=>'c', 'Ц'=>'c', 'Č'=>'c', 'č'=>'c', 'Ч'=>'ch', 'ч'=>'ch',
        'ד'=>'d', 'ď'=>'d', 'Đ'=>'d', 'Ď'=>'d', 'đ'=>'d', 'д'=>'d', 'Д'=>'D', 'ð'=>'d',
        'є'=>'e', 'ע'=>'e', 'е'=>'e', 'Е'=>'e', 'Ə'=>'e', 'ę'=>'e', 'ĕ'=>'e', 'ē'=>'e', 'Ē'=>'e', 'Ė'=>'e', 'ė'=>'e', 'ě'=>'e', 'Ě'=>'e', 'Є'=>'e', 'Ĕ'=>'e', 'ê'=>'e', 'ə'=>'e', 'è'=>'e', 'ë'=>'e', 'é'=>'e',
        'ф'=>'f', 'ƒ'=>'f', 'Ф'=>'f',
        'ġ'=>'g', 'Ģ'=>'g', 'Ġ'=>'g', 'Ĝ'=>'g', 'Г'=>'g', 'г'=>'g', 'ĝ'=>'g', 'ğ'=>'g', 'ג'=>'g', 'Ґ'=>'g', 'ґ'=>'g', 'ģ'=>'g',
        'ח'=>'h', 'ħ'=>'h', 'Х'=>'h', 'Ħ'=>'h', 'Ĥ'=>'h', 'ĥ'=>'h', 'х'=>'h', 'ה'=>'h',
        'î'=>'i', 'ï'=>'i', 'í'=>'i', 'ì'=>'i', 'į'=>'i', 'ĭ'=>'i', 'ı'=>'i', 'Ĭ'=>'i', 'И'=>'i', 'ĩ'=>'i', 'ǐ'=>'i', 'Ĩ'=>'i', 'Ǐ'=>'i', 'и'=>'i', 'Į'=>'i', 'י'=>'i', 'Ї'=>'i', 'Ī'=>'i', 'І'=>'i', 'ї'=>'i', 'і'=>'i', 'ī'=>'i', 'ĳ'=>'ij', 'Ĳ'=>'ij',
        'й'=>'j', 'Й'=>'j', 'Ĵ'=>'j', 'ĵ'=>'j', 'я'=>'ja', 'Я'=>'ja', 'Э'=>'je', 'э'=>'je', 'ё'=>'jo', 'Ё'=>'jo', 'ю'=>'ju', 'Ю'=>'ju',
        'ĸ'=>'k', 'כ'=>'k', 'Ķ'=>'k', 'К'=>'k', 'к'=>'k', 'ķ'=>'k', 'ך'=>'k',
        'Ŀ'=>'l', 'ŀ'=>'l', 'Л'=>'l', 'ł'=>'l', 'ļ'=>'l', 'ĺ'=>'l', 'Ĺ'=>'l', 'Ļ'=>'l', 'л'=>'l', 'Ľ'=>'l', 'ľ'=>'l', 'ל'=>'l',
        'מ'=>'m', 'М'=>'m', 'ם'=>'m', 'м'=>'m',
        'ñ'=>'n', 'н'=>'n', 'Ņ'=>'n', 'ן'=>'n', 'ŋ'=>'n', 'נ'=>'n', 'Н'=>'n', 'ń'=>'n', 'Ŋ'=>'n', 'ņ'=>'n', 'ŉ'=>'n', 'Ň'=>'n', 'ň'=>'n',
        'о'=>'o', 'О'=>'o', 'ő'=>'o', 'õ'=>'o', 'ô'=>'o', 'Ő'=>'o', 'ŏ'=>'o', 'Ŏ'=>'o', 'Ō'=>'o', 'ō'=>'o', 'ø'=>'o', 'ǿ'=>'o', 'ǒ'=>'o', 'ò'=>'o', 'Ǿ'=>'o', 'Ǒ'=>'o', 'ơ'=>'o', 'ó'=>'o', 'Ơ'=>'o', 'œ'=>'oe', 'Œ'=>'oe', 'ö'=>'oe',
        'פ'=>'p', 'ף'=>'p', 'п'=>'p', 'П'=>'p',
        'ק'=>'q',
        'ŕ'=>'r', 'ř'=>'r', 'Ř'=>'r', 'ŗ'=>'r', 'Ŗ'=>'r', 'ר'=>'r', 'Ŕ'=>'r', 'Р'=>'r', 'р'=>'r',
        'ș'=>'s', 'с'=>'s', 'Ŝ'=>'s', 'š'=>'s', 'ś'=>'s', 'ס'=>'s', 'ş'=>'s', 'С'=>'s', 'ŝ'=>'s', 'Щ'=>'sch', 'щ'=>'sch', 'ш'=>'sh', 'Ш'=>'sh', 'ß'=>'ss',
        'т'=>'t', 'ט'=>'t', 'ŧ'=>'t', 'ת'=>'t', 'ť'=>'t', 'ţ'=>'t', 'Ţ'=>'t', 'Т'=>'t', 'ț'=>'t', 'Ŧ'=>'t', 'Ť'=>'t', '™'=>'tm',
        'ū'=>'u', 'у'=>'u', 'Ũ'=>'u', 'ũ'=>'u', 'Ư'=>'u', 'ư'=>'u', 'Ū'=>'u', 'Ǔ'=>'u', 'ų'=>'u', 'Ų'=>'u', 'ŭ'=>'u', 'Ŭ'=>'u', 'Ů'=>'u', 'ů'=>'u', 'ű'=>'u', 'Ű'=>'u', 'Ǖ'=>'u', 'ǔ'=>'u', 'Ǜ'=>'u', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'У'=>'u', 'ǚ'=>'u', 'ǜ'=>'u', 'Ǚ'=>'u', 'Ǘ'=>'u', 'ǖ'=>'u', 'ǘ'=>'u', 'ü'=>'ue',
        'в'=>'v', 'ו'=>'v', 'В'=>'v',
        'ש'=>'w', 'ŵ'=>'w', 'Ŵ'=>'w',
        'ы'=>'y', 'ŷ'=>'y', 'ý'=>'y', 'ÿ'=>'y', 'Ÿ'=>'y', 'Ŷ'=>'y',
        'Ы'=>'y', 'ž'=>'z', 'З'=>'z', 'з'=>'z', 'ź'=>'z', 'ז'=>'z', 'ż'=>'z', 'ſ'=>'z', 'Ж'=>'zh', 'ж'=>'zh'
    );
    return strtr($s, $replace);
}
