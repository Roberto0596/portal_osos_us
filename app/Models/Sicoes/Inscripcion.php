<?php 

namespace App\Models\Sicoes;

use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model {

	protected $connection = 'sicoes';

	protected $primaryKey = 'InscripcionId';

    protected $table = 'Inscripcion';

    public $timestamps = false;

    protected $fillable = [
    	"InscripcionId",
    	"Semestre"
    	"EncGrupoId",
    	"Fecha",
    	"Baja",
    	"AlumnoId",
    ];

}

