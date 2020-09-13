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

    public function Document()
    {
        return $this->belongsTo('App\Models\Alumns\Document');
    }
}