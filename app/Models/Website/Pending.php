<?php

namespace App\Models\Website;

use Illuminate\Database\Eloquent\Model;

class Pending extends Model
{
    protected $table = "pendings";

    protected $fillable = [
    	'id',
        'enrollment',
        'password',
        'status',
        'created_at',
        'updated_at',
    ];
}
