<?php

namespace App\Models\Alumns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{

    protected $table = "users";

    protected $fillable = [
    	'id',
        'name',
        'last_name',
        'email',
        'password',
        'tour',
        'created_at',
        'updated_at',
    ];
}
