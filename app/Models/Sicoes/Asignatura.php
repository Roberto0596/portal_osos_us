<?php 

namespace App\Models\Sicoes;

use Illuminate\Database\Eloquent\Model;

class Asignatura extends Model {

	protected $connection = 'sicoes';

	protected $primaryKey = 'AsignaturaId';

    protected $table = 'Asignatura';

    public $timestamps = false;

    protected $fillable = [
    	"AsignaturaId",
    	"Semestre",
    	"Clave",
    	"Nombre",
    	"Creditos",
    	"HrDocente",
    	"HrIndependiente",
    	"Instalacion",
    	"HoraSemana",
    	"PlanEstudioId",
    	"HaySeriacion",
    ];

}

