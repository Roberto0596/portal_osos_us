<?php

namespace App\Models\Alumns;

use Illuminate\Database\Eloquent\Model;

class HighAverages extends Model
{
    protected $table = "high_averages";

    protected $fillable = [
    	'id',
        'alumn_id',
        'periodo_id',
        'created_at',
        'updated_at',
    ];
}
