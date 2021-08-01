<?php namespace App\Library;

use App\Models\Sicoes\PlanEstudio;
use App\Models\Sicoes\Alumno;
use App\Models\Sicoes\Inscripcion;
use App\Models\Sicoes\EncGrupo;
use App\Models\Sicoes\Carrera;
use App\Models\Alumns\User;
use App\Library\Sicoes;

class Inscription {

    /**
     * realiza el proceso de inscripción.
     *
     * @param  App\Models\Alumns\User $user
     *
     * @return $array
     */
    public static function makeRegister(User $user)
    {
        $message = ["success" => [], "errors" => []];

        $alumno = Alumno::find($user->id_alumno);
        $inscripcionData = Sicoes::checkGroupData($user->id_alumno);

        if ($inscripcionData == false) {
            $inscripcionData = ["Semestre" => 4, "EncGrupoId" => 1120];
            addFailedRegister($user->id, "no se encontro el grupo para este alumno.");
        }

        //entrara en la condicion cuando el alumno sea de nuevo ingreso
        if ($inscripcionData->Semestre == 1) {
            $enrollement = self::generateCarnet($alumno->PlanEstudioId); 
            $alumno->Matricula = $enrollement;
            $alumno->save();          
            $user->email = "a".str_replace("-", "", $enrollement)."@unisierra.edu.mx";
        } 

        $inscribir = self::insertRegister([
            'Semestre' => $inscripcionData["Semestre"],
            'EncGrupoId'=> $inscripcionData["EncGrupoId"],
            'Fecha'=> getDateCustom(),
            'Baja' => 0, 
            'AlumnoId'=>$user->id_alumno,
            'PeriodoId' => getConfig()->period_id,
        ]);

        if ($inscribir) {
            $user->inscripcion = 3;
            $user->save();
            addNotify("Pago de colegiatura", $user->id,"alumn.charge");
            insertInscriptionDocuments($user->id);
            array_push($message["success"], "proceso realizado con exito");
        } else {
            array_push($message["errors"], "No fue posible inscribir al alumno ".$user->name);
        }
        return $message;
    }

    /**
     * inserta el registro de inscripcion.
     *
     * @param  $array|required
     *
     * @return bool
     */
    public static function insertRegister($array)
    {
        try {
            $instance = new Inscripcion;
            $instance->Semestre = $array["Semestre"];
            $instance->EncGrupoId = $array["EncGrupoId"];
            $instance->Fecha = $array["Fecha"];
            $instance->Baja = $array["Baja"];
            $instance->AlumnoId = $array["AlumnoId"];
            $instance->Fecha = $array["Fecha"];
            $instance->PeriodoId = $array["PeriodoId"];
            $instance->save();
            return true;
        } catch(\Exception $e) {
            dd($e);
            return false;
        }
    }

    /**
     * Genera una matricula nueva.
     *
     * @param  int $planEstudioId|required
     *
     * @return String $matricula
     */
    public static function generateCarnet($planEstudioId)
    {
        $plan = PlanEstudio::find($planEstudioId);
        $clave = Carrera::find($plan->CarreraId);
        $date = getDate();
        $year = substr($date["year"], -2);
        $lastAlumn = self::lastEnrollement($planEstudioId, $clave->Clave, $year);

        if (!$lastAlumn) {
            return $year."-".$clave["Clave"]."-0001";
        } else {
            $sum = substr($lastAlumn->Matricula,-4) + 1;
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

    /**
     * Obtiene el ultimo registro de un alumno por plan de estudio.
     *
     * @param  int $planEstudioId|required
     *
     * @param  int $clave|required
     *
     * @param  int $fecha|required
     *
     * @return App\Models\Sicoes\Alumno
     */
    public static function lastEnrollement($planEstudioId,$clave,$fecha)
    {
        $like = $fecha."-".$clave."-%%%%";

        $data = Alumno::where("PlanEstudioId", $planEstudioId)
                            ->where("Matricula", "like", $like)
                            ->orderBy("AlumnoId", "desc")
                            ->first();
        return $data;
    }
}