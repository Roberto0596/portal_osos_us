<?php

namespace App\Models\AdminUsers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model
{
    protected $table = "area";

    protected $fillable = [
    	'id',
        'name',
        'created_at',
        'updated_at',
    ];
}
