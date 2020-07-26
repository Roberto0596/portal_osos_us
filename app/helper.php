<?php 

use App\Models\AdminUsers\AdminUser;
use App\Models\Alumns\Notify;
use App\Models\Alumns\DebitType;

//seccion del sistema
function addNotify($text,$id,$route)
{
  $notify = new Notify();
  $notify->text = $text;
  $notify->alumn_id = $id;
  $notify->route = $route;
  $notify->save();
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
  $getCurrentPeriod = selectCurrentPeriod();
  $array =[['name' => 'constancia de no adeudo', 'route' => 'alumn.constancia', 'PeriodoId' => $getCurrentPeriod["PeriodoId"], 'alumn_id' => $id],['name' => 'cédula de reinscripción', 'route' => 'alumn.cedula', 'PeriodoId' => $getCurrentPeriod["PeriodoId"], 'alumn_id' => $id]
        ];
  $insertDocument = insertIntoPortal("document",$array);
  return $insertDocument;
}

function insertInscriptionDebit($id_alumno)
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

  //inscribimos al alumno despues de pagar
  $inscribir = inscribirAlumno(['Semestre' => $semester,'EncGrupoId'=> 14466,'Fecha'=> getDateCustom(),'Baja'=>0, 'AlumnoId'=>$id_alumno]);

  if ($inscribir)
  {
    return !$inscripcionData?$enrollement:"reinscripcion";
  }
  else
  {
      return false;
  }
}

//este metodo servira para trarnos el periodo actual o en curso
function selectCurrentPeriod()
{
    $stmt = ConectSqlDatabase()->prepare("SELECT top(1) * from Periodo where Semestre <> 'CURSO DE VERANO' order by PeriodoId desc;");
    $stmt->execute();
    return $stmt->fetch();
    $stmt = null;
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
    $baja = chr($array["Baja"]);
    $array = array('Baja' => $baja,
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

//aplica para tablas que tiene un campo nombre 

function getItemClaveAndNamesFromTables($table_name)
{
    $stmt = ConectSqlDatabase()->prepare("SELECT Clave,Nombre FROM $table_name");
    $stmt->execute();
	return $stmt->fetchAll();

}
function getDataByIdAlumn($id_alumn){
    $stmt = ConectSqlDatabase()->prepare("SELECT * FROM alumno WHERE alumnoid = $id_alumn");
    $stmt->execute();
    $response = $stmt->fetchAll();
	return  $response[0];
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

function getCarreraFromIdAlumn($id_alumn){

}
	
function selectAdmin($id = null)
{
    if ($id!=null)
    {
        return AdminUser::find($id);
    }
}

function getAlumno($matricula)
{
    $stmt = ConectSqlDatabase()->prepare("SELECT * FROM Alumno where Matricula = '$matricula'");
    $stmt->execute();
    $alumno = $stmt->fetch();
    return $alumno;
    $stmt = null;
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
    $like = $fecha."-".$clave."-%";
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
    else if (strlen($sum)=="") 
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

function agruparPorSalon($PlanEstudioId,$EncGrupoId)
{
  $stmt = ConectSqlDatabase()->prepare("SELECT a.Nombre, a.Matricula, g.Nombre as Grupo from Alumno as a inner join PlanEstudio as p on a.PlanEstudioId = p.PlanEstudioId inner join EncGrupo as g on g.PlanEstudioId = p.PlanEstudioId where p.PlanEstudioId = :PlanEstudioId and g.EncGrupoId = :EncGrupoId and a.Baja = 0;");
  $stmt->bindParam(":PlanEstudioId", $PlanEstudioId, PDO::PARAM_STR);
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
  $stmt = $link->prepare("SELECT count($field), $field FROM $table GROUP by $field");
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
  $data = getDataByIdAlumn($id_alumno); 
  $inscripcion = getLastThing("Inscripcion","AlumnoId",$id_alumno,"InscripcionId");
  $currentPeriod = selectCurrentPeriod();
  if ($inscripcion!=false)
  {
    $group =  obtenerGrupo(($inscripcion["Semestre"]+1),$data["PlanEstudioId"],$currentPeriod["PeriodoId"]);
  }
  else
  {
    $group =  obtenerGrupo(1,$data["PlanEstudioId"],$currentPeriod["PeriodoId"]);
  }
  return $group;
}