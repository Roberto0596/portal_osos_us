<?php 

function ConectSqlDatabase()
{
	$link = new \PDO("mysql:host=localhost;dbname=sicoes","root","");
	$link->exec("set names utf8");
	return $link;
}


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

//aplica para tablas que teiene un campo nombre 

function getItemClaveAndNamesFromTables($table_name)
{
    $stmt = ConectSqlDatabase()->prepare("SELECT clave,nombre FROM $table_name");
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
    $stmt = ConectSqlDatabase()->prepare("SELECT carreraid,nombre FROM carrera");
    $stmt->execute();
	return $stmt->fetchAll();

}

function updateByIdAlumn($id_alumn,$colName,$value)
{
    $stmt = ConectSqlDatabase()->prepare("update alumno set $colName = :$colName where alumnoid = :alumnoid");
    $stmt->bindParam(":".$colName,$value,PDO::PARAM_STR);
    $stmt->bindParam(":alumnoid",$id_alumn,PDO::PARAM_INT);
    $stmt->execute();
	
}