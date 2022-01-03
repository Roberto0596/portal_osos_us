<?php namespace App\Library;

use App\Models\Sicoes\PlanEstudio;
use App\Models\Sicoes\Alumno;
use App\Models\Sicoes\Inscripcion;
use App\Models\Sicoes\EncGrupo;
use App\Models\Sicoes\Carrera;
use App\Models\Alumns\User;
use App\Library\Sicoes;
use App\Models\Alumns\FailedRegister;

class Inscription {

    /**
     * realiza el proceso de inscripción.
     *
     * @param  App\Models\Alumns\User $user
     *
     * @return $array
     */
    public static function makeRegister(Alumno $alumno)
    {
        $inscripcionData = Sicoes::checkGroupData($alumno->AlumnoId);

        $user = User::where("id_alumno", $alumno->AlumnoId)->first();

        if ($inscripcionData == false || $inscripcionData == null) {
            $inscripcionData = ["Semestre" => 4, "EncGrupoId" => 1120];
            self::addFailedRegister($user->id, "no se encontro el grupo para este alumno.");
        }

        $semestre = isset($inscripcionData->Semestre) ? $inscripcionData->Semestre : $inscripcionData["Semestre"];
        $encgrupo = isset($inscripcionData->EncGrupoId) ? $inscripcionData->EncGrupoId : $inscripcionData["EncGrupoId"];

        $inscribir = self::insertRegister([
            'Semestre' => $semestre,
            'EncGrupoId'=> $encgrupo,
            'Fecha'=> getDateCustom(),
            'Baja' => 0, 
            'AlumnoId'=>$user->id_alumno,
            'PeriodoId' => getConfig()->period_id,
        ]);

        if ($inscribir) {
            $user->nextStep(3);
            insertInscriptionDocuments($user->id);
            $result = (Object) [
                "status" => "success", 
                "message" => "Inscripcion Correcta"
            ];
        } else {
            $result = (Object) [
                "status" => "error", 
                "message" => "No fue posible inscribir al alumno " . $alumno->FullName
            ];
        }

        return $result;
    }

     /**
     * agrega un fallo de inscripcion.
     *
     * @param  $id|required
     *
     * @param  $message|required
     *
     * @return void
     */
    public static function addFailedRegister($id, $message) {
        $instance = new FailedRegister();
        $instance->alumn_id = $id;
        $instance->period_id = selectCurrentPeriod()->id;
        $instance->message = $message;
        $instance->status = 0;
        $instance->save();
    }

    public static function assignEnrollment(User $user) {
        $enrollement = self::generateCarnet($user->sAlumn->PlanEstudioId); 
        $user->sAlumn->Matricula = $enrollement;
        $user->sAlumn->save();          
        $user->email = "a".str_replace("-", "", $enrollement)."@unisierra.edu.mx";
        $user->save();
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