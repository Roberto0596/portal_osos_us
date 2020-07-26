<?php

namespace App\Models\AdminUsers;

use Illuminate\Database\Eloquent\Model;

class Problem extends Model
{

    protected $table = "problem";

    protected $fillable = [
        'id',
        'alumn_id',
        'text',
        'status',
        'created_at',
        'updated_at',
    ];
}