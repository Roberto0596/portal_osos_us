<?php

namespace App\Models\Alumns;

use Illuminate\Database\Eloquent\Model;

class Notify extends Model
{

    protected $table = "notify";

    protected $fillable = [
    	'id',
        'text',
        'alumn_id',
        'status',
        'created_at',
        'updated_at',
    ];
}
