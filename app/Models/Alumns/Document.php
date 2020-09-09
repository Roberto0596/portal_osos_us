<?php

namespace App\Models\Alumns;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{

    protected $table = "document";

    protected $fillable = [
    	'id',
        'name',
        'route',
        'status',
        'PeriodoId',
        'alumn_id',
        'created_at',
        'updated_at',
        'type',
    ];
}