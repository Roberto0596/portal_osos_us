<?php 

namespace App\Library;

use App\Models\Alumns\User;
use App\Models\Sicoes\Asignatura;
use App\Models\Sicoes\DetGrupo;
use App\Models\Sicoes\Seriacions;
use App\Models\Sicoes\Carga;

class DesicionTree {

	private $realCharge;
	private $current_period;

	public function __construct() {
		$this->realCharge = collect();
		$this->current_period = selectCurrentPeriod();
	}

	function saveCharge($charge = null)	{
		
		if ($charge == null) {
			$charge = $this->realCharge;
		}	

		try {
			foreach ($charge as $key => $value) {
				$carga = new Carga();
				$carga->Baja = $value->baja;
				$carga->AlumnoId = $value->alumnoId;
				$carga->DetGrupoId = $value->detGrupoId;
				$carga->PeriodoId = $value->periodoId;
				$carga->save();
			}
			return true;
		} catch(\Exception $e) {
			return false;
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
		$alumnData = $user->sAlumn;
		$asignaturas = $this->getAsignaturas($alumnData->PlanEstudioId);
		$mergedCharge = collect();

		foreach ($asignaturas as $key => $value) {
			$carga = $this->alumnCharge($value->AsignaturaId, $alumnData->AlumnoId);
			$detGrupo = $this->DetGrupoId($value->AsignaturaId);

			if ($detGrupo) {
				$push = [
					"calificacion" => isset($carga->Calificacion) ? $carga->Calificacion : null,
					"nombre" => $alumnData->Nombre,
					"alumnoId" => $user->id_alumno,
					"materia" => $value->Nombre,
					"asignaturaId" => $value->AsignaturaId,
					"semestre" => $value->Semestre,
					"haySeriacion" => $value->HaySeriacion,
					"detGrupoId" => $detGrupo->DetGrupoId,
					"periodoId" => $this->current_period->id,
					"nombreProfesor" => $detGrupo->Nombre ." ". $detGrupo->ApellidoPrimero,
					"baja" => 0
				];
				$mergedCharge->push((Object) $push);
			}
		}
		return $mergedCharge;
	}

	
	private function getAsignaturaSeriada($id_asignatura = 547, $id_alumno =635) {
		$seriada = $this->getSeriacion($id_asignatura);

		if ($seriada) {
			$data = Asignatura::join("DetGrupo as det", "Asignatura.AsignaturaId", "=", "det.AsignaturaId")
							->join("Carga as c", "det.DetGrupoId", "=", "c.DetGrupoId")
							->where("Asignatura.AsignaturaId", $seriada->AsignaturaIdSeriada)
							->where("c.AlumnoId", $id_alumno)
							->orderBy("c.CargaId", "desc");
			return $data->first();
		}
	}

	private function getSeriacion($id_asignatura) {
		$data = Seriacions::where("AsignaturaId", $id_asignatura)->orderBy("SeriacionId", "desc")->take(1);
		return $data->first();
	}

	private function alumnCharge($asignaturaId,$alumn_id) {
		$data = DetGrupo::join("Carga as car", "DetGrupo.DetGrupoId", "=", "car.DetGrupoId")
						->join("Alumno as alu", "car.AlumnoId", "=", "alu.AlumnoId")
						->where("DetGrupo.AsignaturaId", $asignaturaId)
						->where("car.AlumnoId", $alumn_id)
						->orderBy("car.CargaId", "desc")
						->select("car.AlumnoId", "car.Calificacion", "alu.Nombre")
						->first();
		return $data;
	}

	private function DetGrupoId($id_asignatura) {
		$data = DetGrupo::join("Profesor", "DetGrupo.ProfesorId", "=", "Profesor.ProfesorId")
						->where("AsignaturaId", $id_asignatura)
						->orderBy("DetGrupoId", "desc")
						->first();
		return $data;
	}

	private function getAsignaturas($plan) {
		$query = Asignatura::where("PlanEstudioId", $plan)->orderBy("Semestre");
		return $query->get();
	}

	public function makeTree(User $user) {
		$asignaturas = $this->mergeCharge($user);
		$current_semester = $user->getLastInscription() ? $user->getLastInscription()->Semestre : "1";
		$odd = [1,3,5,7,9];
  		$pair = [2,4,6,8];

		foreach ($asignaturas as $key => $value) {
			$status = true;
			$node = 0;
			do {
				switch ($node) {
					case 0:
						if ($this->current_period->semestre == "1") {
							$node = in_array(intval($value->semestre), $pair) ? 2 : 1;
						} else {
							$node = in_array(intval($value->semestre), $odd) ? 2 : 1;
						}
						break;
					case 1:
						$status = false;
						break;
					case 2:
						$node = (intval($value->semestre) <= intval($current_semester)) ? 4 : 3;
						break;
					case 3:
						$status = false;
						break;
					case 4: 
						$node = (intval($value->haySeriacion) == 1) ? 5 : 6;
						break;
					case 5: 
						$seriacion = $this->getAsignaturaSeriada($value->asignaturaId, $user->id_alumno);

						if (isset($seriacion->Calificacion)) {
							$node = (intval($seriacion->Calificacion) >= 70) ? 8 : 7;
						} else {
							$status = false;
						}
						break;
					case 6:
						$node = ($value->calificacion == null || intval($value->calificacion) < 70) ? 10 : 9;
						break;
					case 7:
						$status = false;
						break;
					case 8:
						$node = ($value->calificacion == null || intval($value->calificacion) < 70) ? 12 : 11;
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

		return $this;
	}
}