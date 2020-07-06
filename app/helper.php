<?php 

use App\Models\AdminUsers\AdminUser;

function ConectSqlDatabase()
{
    $password = "admin123";
    $user = "robert";
    $rutaServidor = "127.0.0.1";
	$link = new PDO("sqlsrv:server=.\SQLEXPRESS01;database=sicoes", $user, $password);
    $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $link;
}

//este metodo servira para trarnos el periodo actual o en curso
function selectCurrentPeriod()
{
    $stmt = ConectSqlDatabase()->prepare("SELECT top(1) * from Periodo order by PeriodoId desc;");
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
    $period = selectCurrentPeriod();
    $lastCharge = selectLastCharge($alumnId);
    $detgrupo = selectSicoes("DetGrupo","DetGrupoId",$lastCharge["DetGrupoId"])[0];
    $asignature = selectSicoes("Asignatura","AsignaturaId",$detgrupo["AsignaturaId"])[0];
    return $asignature["Semestre"];
}

//metodo para traernos un array con las materias que el alumno puede llevar
function getCurrentAsignatures($alumnId)
{
    $alumnData = selectSicoes("alumno","AlumnoId",$alumnId)[0];
    $currentSemester = getLastSemester($alumnId) + 1;
    return getAsignatures($currentSemester,$alumnData["PlanEstudioId"]);
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
    $link = new \PDO("mysql:host=localhost;dbname=sicoes","root","");
    $link->exec("set names utf8");
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
    $sql = "UPDATE users SET name=?, surname=?, sex=? WHERE id=?";
    $stmt= $pdo->prepare($sql);
    $stmt->execute([$name, $surname, $sex, $id]);
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