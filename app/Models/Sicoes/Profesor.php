<?php 

namespace App\Models\Sicoes;

use Illuminate\Database\Eloquent\Model;

class Profesor extends Model {

	protected $connection = 'sicoes';

	protected $primaryKey = 'ProfesorId';

    protected $table = 'Profesor';

    public $timestamps = false;

    protected $fillable = [
    	"ProfesorId",
    	"NumEmpleado"
    	"Nombre",
    	"ApellidoPrimero",
    	"ApellidoSegundo",
    	"Email",
    	"Foto",
    ];

}

