<?php 

namespace App\Models\Sicoes;

use Illuminate\Database\Eloquent\Model;

class PlanEstudio extends Model {

	protected $connection = 'sicoes';

	protected $primaryKey = 'PlanEstudioId';

    protected $table = 'PlanEstudio';

    public $timestamps = false;

    protected $fillable = [
    	"PlanEstudioId",
    	"Clave"
    	"Nombre",
    	"Modalidad",
    	"Duracion",
    	"Objetivo",
    	"CarreraId",
    ];

}

