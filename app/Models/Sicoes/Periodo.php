<?php 

namespace App\Models\Sicoes;

use Illuminate\Database\Eloquent\Model;

class Periodo extends Model {

	protected $connection = 'sicoes';

	protected $primaryKey = 'PeriodoId';

    protected $table = 'Periodo';

    public $timestamps = false;

    protected $fillable = [
    	"Clave",
        "Anio",
        "Ciclo",
        "FechaInicio",
        "FechaTermino",
        "Vigente",
        "Semestre"
    ]; 
}
