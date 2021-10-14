<?php

namespace App\Models\Alumns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Sicoes\Inscripcion;
use App\Library\Sicoes;
use App\Library\Inscription;

class User extends Authenticatable
{
    protected $table = "users";

    protected $with = ["sAlumn"];

    protected $appends = ["FullName"];

    public $steps = [
        "form" => 0,
        "payment" => 1,
        "waiting" => 2,
        "charge" => 3,
        "complete" => 4
    ];
    
    public function document() {
        return $this->hasMany('\App\Models\Alumns\Document', "alumn_id", "id");
    }

    public function sAlumn() {
        return $this->belongsTo("\App\Models\Sicoes\Alumno", "id_alumno", "AlumnoId");
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

    public function nextStep($custom = null) {
        if (getConfig()->open_inscription == 1) {

            if ($this->is_in_back_inscription == 1) {
                $this->inscripcion = $this->back_inscripcion;
                $this->is_in_back_inscription = 0;
                $this->back_inscripcion = null;
            } else {
                if (!$custom) {
                    if ($this->inscripcion < 4) {
                        $this->inscripcion = $this->inscripcion + 1;
                    }
                } else {
                    if ($custom < 4) {
                        $this->inscripcion = $custom;
                    }
                }  
            }
    
            $this->save();          
        }
    }

    public function closeProccess() {
        $this->inscripcion = $this->steps["complete"];
        if($this->getLastInscription()->Semestre == 1) {
            Inscription::assignEnrollment($this);
        }
        $this->save();

        //setear los adeudos relacionados con el alumno
        $debits = Debit::where("id_alumno", $this->id_alumno)->get();

        foreach ($debits as $key => $value) {
            $value->setForeignValues();
        }
    }

    public function current_group() {
        $group = Sicoes::currentGroup($this->id_alumno);
        return $group;
    }

    public function getMatricula() {
        return $this->sAlumn->Matricula;
    }

    public function getLastInscription() {
        return Sicoes::getLastInscription($this->id_alumno);
    }

    public function currentInscription() {
        $inscription = Inscripcion::where("AlumnoId", $this->id_alumno)
                        ->where("Baja", 0)
                        ->orderBy("InscripcionId", "desc")
                        ->first();
        return $inscription;
    }

    public function getFullNameAttribute() {
        return join(" ", [$this->name, $this->lastname]);
    }
}
