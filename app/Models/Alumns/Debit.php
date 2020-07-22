<?php

namespace App\Models\Alumns;

use Illuminate\Database\Eloquent\Model;

class Debit extends Model
{

    protected $table = "debit";

    protected $fillable = [
    	'id',
        'debit_type_id',
        'description',
        'amount',
        'admin_id',
        'id_alumno',
        'status',
        'created_at',
        'updated_at',
    ];
}
