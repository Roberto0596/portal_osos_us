<?php

namespace App\Models\Alumns;

use Illuminate\Database\Eloquent\Model;

class FailedRegister extends Model
{
    protected $table = "failed_register";

    protected $fillable = [
    	'id',
        'alumn_id',
        'period_id',
        'message',
        'status',
        'created_at',
        'updated_at',
    ];

    public function alumn() {
        return $this->belongsTo('\App\Models\Alumns\User', "alumn_id", "id");
    }

        public function period() {
        return $this->belongsTo('\App\Models\PeriodModel', "period_id", "id");
    }
}
