<?php

namespace App\Models\Alumns;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = "ticket";

    protected $fillable = [
    	'id',
        'concept',
        'route',
        'alumn_id',
        'debit_id',
        'created_at',
        'updated_at',
    ];

    public function alumn() {
        return $this->belongsTo('\App\Models\Alumns\User', "alumn_id", "id");
    }


    public function documentType()
    {
        return $this->belongsTo('App\Models\Alumns\Debit','debit_id','id');
    }
}
