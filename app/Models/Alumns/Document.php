<?php

namespace App\Models\Alumns;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{

    protected $table = "document";

    protected $fillable = [
    	'id',
        'description',
        'route',
        'status',
        'PeriodoId',
        'alumn_id',
        'created_at',
        'updated_at',
        'type',
        'document_type_id',
    ];

    // public function DocumentType()
    // {
    //     return $this->hasOne('App\Models\Alumns\DocumentType');
    // }

    public function DocumentType()
    {
        return $this->belongsTo('App\Models\Alumns\DocumentType','document_type_id','id');
    }
}