<?php 

use App\Models\AdminUsers\AdminUser;

function ConectSqlDatabase()
{
	$link = new \PDO("mysql:host=localhost;dbname=sicoes","root","");
	$link->exec("set names utf8");
	return $link;
}

//este metodo servira para trarnos el periodo actual o en curso
function selectCurrentPeriod()
{
    $stmt = ConectSqlDatabase()->prepare("SELECT * FROM periodo order by periodoid desc limit 1");
    $stmt->execute();
    return $stmt->fetch();
    $stmt = null;
}

//metodo auxiliar para saber las ultimas cargas del alumno
function selectLastCharge($alumnoid)
{
    $stmt = ConectSqlDatabase()->prepare("SELECT * FROM carga where alumnoid = :alumnoid order by cargaid desc limit 1");
    $stmt->bindParam(":alumnoid",$alumnoid,PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch();
    $stmt = null;
}

//metodo que nos da el ultimo semestre en el que estuvo el alumno, de manera que, el resultado de este metodo se le subara 1.
function getLastSemester($alumnId)
{
    $period = selectCurrentPeriod();
    $lastCharge = selectLastCharge($alumnId);
    $detgrupo = selectSicoes("detgrupo","detgrupoid",$lastCharge["detgrupoid"])[0];
    $asignature = selectSicoes("asignatura","asignaturaid",$detgrupo["asignaturaid"])[0];
    return $asignature["semestre"];
}

//metodo para traernos un array con las materias que el alumno puede llevar
function getCurrentAsignatures($alumnId)
{
    $alumnData = selectSicoes("alumno","alumnoid",$alumnId)[0];
    $currentSemester = getLastSemester($alumnId) + 1;
    return getAsignatures($currentSemester,$alumnData["planestudioid"]);
}

//metodo que nos trae todas las asignaturas
function getAsignatures($semester,$planid)
{
    $stmt = ConectSqlDatabase()->prepare("SELECT * FROM asignatura where semestre = :semestre and planestudioid = :planestudioid");
    $stmt->bindParam(":semestre",$semester,PDO::PARAM_STR);
    $stmt->bindParam(":planestudioid",$planid,PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll();
    $stmt = null;
}

function getDetGrupo($asignaturaid)
{
    $stmt = ConectSqlDatabase()->prepare("SELECT * FROM detgrupo where asignaturaid = :asignaturaid order by detgrupoid desc limit 1");
    $stmt->bindParam(":asignaturaid",$asignaturaid,PDO::PARAM_INT);
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
			$stmt = ConectSqlDatabase()->prepare("SELECT * FROM $table_name limit :bol");
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
        $stmt = ConectSqlDatabase()->prepare("DELETE FROM carga where cargaid = :cargaid");
        $stmt->bindParam(":cargaid",$value, PDO::PARAM_INT);
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
    $stmt = $link->prepare("INSERT INTO carga(baja,alumnoid,detgrupoid,periodoid) values(:baja,:alumnoid,:detgrupoid,:periodoid)");
    $baja = chr($array["baja"]);
    $stmt->bindParam(":baja",$baja,PDO::PARAM_STR);
    $stmt->bindParam(":alumnoid",$array["alumnoid"],PDO::PARAM_STR);
    $stmt->bindParam(":detgrupoid",$array["detgrupoid"],PDO::PARAM_STR);
    $stmt->bindParam(":periodoid",$array["periodoid"],PDO::PARAM_STR);
    if($stmt->execute())
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

function selectAdmin($id = null)
{
    if ($id!=null)
    {
        return AdminUser::find($id);
    }
}