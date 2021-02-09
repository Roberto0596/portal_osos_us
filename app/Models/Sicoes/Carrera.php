<?php 

namespace App\Models\Sicoes;

use Illuminate\Database\Eloquent\Model;

class Carrera extends Model {

	protected $connection = 'sicoes';

	protected $primaryKey = 'CarreraId';

    protected $table = 'Carrera';

    public $timestamps = false;

    protected $fillable = [
    	"CarreraId",
        "Clave",
        "Nombre",
        "Abreviatura",
        "DivisionId",
    	
    ];

}

