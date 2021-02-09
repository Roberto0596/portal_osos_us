<?php 

namespace App\Models\Sicoes;

use Illuminate\Database\Eloquent\Model;

class DetGrupo extends Model {

	protected $connection = 'sicoes';

	protected $primaryKey = 'DetGrupoId';

    protected $table = 'DetGrupo';

    public $timestamps = false;

    protected $fillable = [
    	"DetGrupoId",
    	"EncGrupoId",
    	"ProfesorId",
    	"AsignaturaId",
    	"Activo",
    	
    ]; 
}
