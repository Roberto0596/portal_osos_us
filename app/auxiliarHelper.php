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

function inscribirAlumno($array)
{
    unset($array["PeriodoId"]);
    // $stmt = ConectSqlDatabase()->prepare("INSERT INTO Inscripcion(Semestre,EncGrupoId,Fecha,Baja,AlumnoId, PeriodoId) values(:Semestre,:EncGrupoId,:Fecha,:Baja,:AlumnoId,:PeriodoId)");

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