<?php

namespace App\Models\Alumns;

use Illuminate\Database\Eloquent\Model;

class PasswordRequest extends Model
{
    protected $table = "request_password";

    protected $fillable = [
    	'id',
        'token',
        'alumn_id',
        'created_at',
        'updated_at',
    ];

    public function alumn() {
        return $this->belongsTo('\App\Models\Alumns\User', "alumn_id", "id");
    }
}
