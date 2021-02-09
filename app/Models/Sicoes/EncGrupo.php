<?php 

namespace App\Models\Sicoes;

use Illuminate\Database\Eloquent\Model;

class EncGrupo extends Model {

	protected $connection = 'sicoes';

	protected $primaryKey = 'EncGrupoId';

    protected $table = 'EncGrupo';

    public $timestamps = false;

    protected $fillable = [
    	"EncGrupoId",
    	"Semestre",
    	"Numero",
    	"Nombre",
    	"PeriodoId",
        "PlanEstudioId",
        "Activo"
    ]; 
}
