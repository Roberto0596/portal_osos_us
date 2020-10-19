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

    public function debitType() {
        return $this->belongsTo('\App\Models\Alumns\DebitType', "debit_type_id", "id");
    }

    public function alumn() {
        return $this->belongsTo('\App\Models\Alumns\User', "id_alumno", "id");
    }

    public function getDebit() {
        return User::find($this->ig_alumno);
    }
}
