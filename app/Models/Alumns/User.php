<?php

namespace App\Models\Alumns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{

    protected $table = "users";

    protected $fillable = [
    	'id',
        'name',
        'last_name',
        'email',
        'password',
        'tour',
        'created_at',
        'updated_at',
    ];

    public function document() {
        return $this->hasMany('\App\Models\Alumns\Document', "alumn_id", "id");
    }

    public function getSicoesData() {
        $data = selectSicoes("Alumno","AlumnoId",$this->id_alumno);

        if ($data) {
            return $data[0];
        } else {
            return null;
        }
    }

    public function getMatricula() {
        $data = selectSicoes("Alumno","AlumnoId",$this->id_alumno);

        if ($data) {
            return $data[0]["Matricula"];
        } else {
            return null;
        }

    }
}
