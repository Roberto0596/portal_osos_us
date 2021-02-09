<?php 

namespace App\Models\Sicoes;

use Illuminate\Database\Eloquent\Model;

class Estado extends Model {

	protected $connection = 'sicoes';

	protected $primaryKey = 'EstadoId';

    protected $table = 'Estado';

    public $timestamps = false;

    protected $fillable = [
    	"EstadoId",
        "Clave",
        "Nombre",
        "Abreviatura",
    ];

}

