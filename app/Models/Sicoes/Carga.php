<?php 

namespace App\Models\Sicoes;

use Illuminate\Database\Eloquent\Model;

class Carga extends Model {

	protected $connection = 'sicoes';

	protected $primaryKey = 'CargaId';

    protected $table = 'Carga';

    public $timestamps = false;

    protected $fillable = [
    	"CargaId",
    	"Calificacion",
    	"Baja",
    	"AlumnoId",
    	"DetGrupoId",
    	"PeriodoId"
    ]; 
}

