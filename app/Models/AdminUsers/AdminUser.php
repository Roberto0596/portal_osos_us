<?php

namespace App\Models\AdminUsers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminUser extends Authenticatable
{

    protected $table = "admin_users";

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
