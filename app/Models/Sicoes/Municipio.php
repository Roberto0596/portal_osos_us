<?php 

namespace App\Models\Sicoes;

use Illuminate\Database\Eloquent\Model;

class Municipio extends Model {

	protected $connection = 'sicoes';

	protected $primaryKey = 'MunicipioId';

    protected $table = 'Municipio';

    public $timestamps = false;

    protected $fillable = [
    	"MunicipioId",
        "Clave",
        "Nombre",
        "EstadoId",
    ];

    protected $with = ["Estado"];

    public function Estado() {
        return $this->belongsTo("\App\Models\Sicoes\Estado", "EstadoId", "EstadoId");
    }

}

