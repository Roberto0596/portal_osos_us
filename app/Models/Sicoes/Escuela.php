<?php 

namespace App\Models\Sicoes;

use Illuminate\Database\Eloquent\Model;

class Escuela extends Model {

	protected $connection = 'sicoes';

	protected $primaryKey = 'EscuelaId';

    protected $table = 'Escuela';

    public $timestamps = false;
}

