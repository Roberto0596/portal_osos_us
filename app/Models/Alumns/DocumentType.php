<?php

namespace App\Models\Alumns;

use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{

    protected $table = "document_type";

    protected $fillable = [
    	'id',
        'name',
        'created_at',
        'updated_at',
    ];

    // public function DocumentType()
    // {
    //     return $this->belongsTo('App\Models\Alumns\Document','id','document_type_id');
    // }

     public function document()
    {
        return $this->hasOne('App\Models\Alumns\Document', "id", "document_type_id");
    }
}