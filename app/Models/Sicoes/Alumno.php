<?php namespace App\Models\Sicoes;

use Illuminate\Database\Eloquent\Model;

class Alumno extends Model {

	protected $connection = 'sicoes';

	protected $primaryKey = 'AlumnoId';

    protected $table = 'Alumno';

    public $timestamps = false;

    protected $fillable = [
    	"AlumnoId",
    	"Nombre",
    ];

    public function pAlumn() {
    	return $this->hasOne("\App\Models\Alumn\User", "AlumnoId", "id_alumno");
    }
}

