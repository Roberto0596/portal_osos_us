<?php

namespace App\Models\AdminUsers;

use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    protected $table = "document_type";

    protected $fillable = [
        'id',
        'name',
        'created_at',
        'updated_at',
        'type',
        'cost',
        'can_delete'
    ];
}
