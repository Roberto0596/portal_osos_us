<?php 

namespace App\Library;

use App\Models\Alumns\User;
use Illuminate\Database\Eloquent\Collection;

class DesicionTree
{
	private $realCharge;

	public function __construct() {
		$this->realCharge = collect();
	}

	function saveCharge($charge = null)
	{
		if ($charge == null) {
			$charge = $this->realCharge;
		}

		foreach ($charge as $key => $value) {
			$this->chargeSaveQuery($value);
		}

	}

	public function chargeSaveQuery($index) {

	    $stmt = ConectSqlDatabase()->prepare("INSERT INTO Carga(Baja,AlumnoId,DetGrupoId,PeriodoId) values(:Baja,:AlumnoId,:DetGrupoId,:PeriodoId)");
	    $array = array('Baja' => $index->baja,
	                    'AlumnoId' => $index->alumnoId,
	                    'DetGrupoId' => $index->detGrupoId,
	                    'PeriodoId' => $index->periodoId);
	    if($stmt->execute($array))
	    {
	        return ConectSqlDatabase()->lastInsertId();
	    }
	    else
	    {
	        return false;
	    }
	    $stmt = null;
	}

	public function makeTree(User $user) {
		$asignaturas = $this->mergeCharge($user);
		$current_semester = $user->getLastInscription()->Semestre;
		$period = selectCurrentPeriod();
		$odd = [1,3,5,7,9];
  		$pair = [2,4,6,8];

		foreach ($asignaturas as $key => $value) {
			$status = true;
			$node = 0;
			do {
				switch ($node) {
					case 0:
						if ($period->semestre == "1") {
							if (in_array(intval($value->semestre), $pair)) {
								$node = 2;
      						} else {
      							$node = 1;
      						}
						} else {
							if (in_array(intval($value->semestre), $odd)) {
								$node = 2;
      						} else {
      							$node = 1;
      						}
						}
						break;
					case 1:
						$status = false;
						break;
					case 2:
						if(intval($value->semestre) <= intval($current_semester)) {
							$node = 4;
						} else {
							$node = 3;
						}
						break;
					case 3:
						$status = false;
						break;
					case 4: 
						if(intval($value->haySeriacion) == 1) {
							$node = 5;
						} else {
							$node = 6;
						}
						break;
					case 5: 
						$seriacion = $this->getAsignaturaSeriada($value->asignaturaId, $user->id_alumno);
						if(intval($seriacion["Calificacion"]) >= 70) {
							$node = 8;
						} else {
							$node = 7;
						}
						break;
					case 6:
						if($value->calificacion == null || intval($value->calificacion) < 70) {
							$node = 10;
						} else {
							$node = 9;
						}
						break;
					case 7:
						$status = false;
						break;
					case 8:
						if($value->calificacion == null || intval($value->calificacion) < 70) {
							$node = 12;
						} else {
							$node = 11;
						}
						break;
					case 9:
						$status = false;
						break;
					case 10:
						$this->realCharge->push($value);
						$status = false;
						break;
					case 11:
						$status = false;
						break;
					case 12:
						$this->realCharge->push($value);
						$status = false;
						break;					
				}
			} while ($status);
		}
	}

	public function getTreeCharge() {
		if ($this->realCharge->count() > 0) {
			return $this->realCharge;
		} else {
			return false;
		}
	}

	private function mergeCharge(User $user) {
		$alumnData = $user->getSicoesData();
		$asignaturas = $this->getAsignaturas($alumnData["PlanEstudioId"]);
		$historial = collect();
		$period = selectCurrentPeriod();
		foreach ($asignaturas as $key => $value) {
			$carga = $this->alumnCharge($value["AsignaturaId"], $alumnData["AlumnoId"]);
			$detGrupo = $this->DetGrupoId($value["AsignaturaId"]);
			$push = [
				"calificacion" => isset($carga["Calificacion"]) ? $carga["Calificacion"] : null,
				"nombre" => isset($carga["Nombre"]) ? $carga["Nombre"] : null,
				"alumnoId" => $user->id_alumno,
				"materia" => $value["Nombre"],
				"asignaturaId" => $value["AsignaturaId"],
				"semestre" => $value["Semestre"],
				"haySeriacion" => $value["HaySeriacion"],
				"detGrupoId" => $detGrupo["DetGrupoId"],
				"periodoId" => $period->id,
				"nombreProfesor" =>$detGrupo["Nombre"] ." ". $detGrupo["ApellidoPrimero"],
				"baja" => 0
			];
			$historial->push((Object) $push);
		}

		return $historial;
	}

	private function getAsignaturaSeriada($id_asignatura = 547, $id_alumno =635) {
		$id_extra = $this->getSeriacion($id_asignatura);
		if ($id_extra) {
			$id_extra = $id_extra["AsignaturaIdSeriada"];
			$query = "SELECT TOP(1) * from Asignatura as asig
				inner join DetGrupo as det on asig.AsignaturaId = det.AsignaturaId
				inner join Carga as carga on det.DetGrupoId = carga.DetGrupoId
				where asig.AsignaturaId = '$id_extra' and AlumnoId = '$id_alumno' order by carga.CargaId desc;";
			$stmt = ConectSqlDatabase()->prepare($query);
			$stmt->execute();
			return $stmt->fetch();
		}
	}

	private function getSeriacion($id_asignatura) {
		$query = "SELECT  TOP(1) * from Seriacions where AsignaturaId = '$id_asignatura' order by SeriacionId desc";
		$stmt = ConectSqlDatabase()->prepare($query);
		$stmt->execute();
		return $stmt->fetch();
	}

	private function alumnCharge($asignaturaId,$alumn_id) {
		$query = "SELECT top(1) car.AlumnoId, car.Calificacion, alu.Nombre from DetGrupo as det 
					inner join Carga as car on det.DetGrupoId = car.DetGrupoId
					inner join Alumno as alu on alu.AlumnoId = car.AlumnoId
					where det.AsignaturaId = '$asignaturaId' and car.AlumnoId = '$alumn_id' order by car.CargaId desc;";
		$stmt = ConectSqlDatabase()->prepare($query);
		$stmt->execute();
		return $stmt->fetch();
		$stmt = null;
	}

	private function DetGrupoId($id_asignatura) {
		$query = "SELECT top(1) * from DetGrupo
				inner join Profesor on DetGrupo.ProfesorId = Profesor.ProfesorId
				where AsignaturaId = '$id_asignatura' order by DetGrupoId desc;";
		$stmt = ConectSqlDatabase()->prepare($query);
		$stmt->execute();
		return $stmt->fetch();
		$stmt = null;
	}

	private function getAsignaturas($plan) {
		$query = "SELECT * from Asignatura  where PlanEstudioId = '$plan' order by Semestre;";
		$stmt = ConectSqlDatabase()->prepare($query);
		$stmt->execute();
		return $stmt->fetchAll();
		$stmt = null;
	}
}

 ?>