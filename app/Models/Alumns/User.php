<?php

namespace App\Models\Alumns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Sicoes\Inscripcion;

class User extends Authenticatable
{

    protected $table = "users";

    protected $with = ["sAlumn"];
    
    public function document() {
        return $this->hasMany('\App\Models\Alumns\Document', "alumn_id", "id");
    }

    public function current_group() {
        $group = current_group($this->id_alumno);
        return isset($group["Nombre"]) ? $group["Nombre"] : false;
    }

    public function getSicoesData() {
        $data = selectSicoes("Alumno","AlumnoId",$this->id_alumno);

        if ($data) {
            return $data[0];
        } else {
            return null;
        }
    }

    public function sAlumn() {
        return $this->belongsTo("\App\Models\Sicoes\Alumno", "id_alumno", "AlumnoId");
    }

    public function getMatricula() {
        $data = selectSicoes("Alumno","AlumnoId",$this->id_alumno);

        if ($data) {
            return $data[0]["Matricula"];
        } else {
            return null;
        }

    }

    public function getLastInscription() {
        return (Object) getInscriptionData($this->id_alumno);
    }

    public function currentInscription() {
        $inscription = Inscripcion::where("AlumnoId", $this->id_alumno)
                        ->where("Baja", 0)
                        ->orderBy("InscripcionId", "desc")
                        ->first();
        return $inscription;
    }

    public function debit() {
        return $this->hasMany('\App\Models\Alumns\Debit', "id", "id_alumno");
    }

    public function failed_register() {
        return $this->hasMany('\App\Models\Alumns\FailedRegister', "id", "alumn_id");
    }

    public function requestPassword() {
        return $this->hasMany('\App\Models\Alumns\PasswordRequest', "id", "alumn_id");
    }
}
