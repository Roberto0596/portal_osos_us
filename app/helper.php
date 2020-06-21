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