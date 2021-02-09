<?php 

namespace App\Models\Sicoes;

use Illuminate\Database\Eloquent\Model;

class Seriacions extends Model {

	protected $connection = 'sicoes';

	protected $primaryKey = 'SeriacionId';

    protected $table = 'Seriacions';

    public $timestamps = false;

    protected $fillable = [
    	"SeriacionId",
    	"AsignaturaId",
    	"AsignaturaIdSeriada"
    ];
}

