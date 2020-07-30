<?php

namespace App\Models\AdminUsers;

use Illuminate\Database\Eloquent\Model;

class RequestPass extends Model
{
    protected $table = "request_pass";

    protected $fillable = [
    	'id',
        'name',
        'last_name',
        'email',
        'created_at',
        'updated_at',
    ];
}
