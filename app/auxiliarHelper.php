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