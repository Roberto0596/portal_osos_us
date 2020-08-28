<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfigModel extends Model
{

    protected $table = "config";

    protected $fillable = [
        'id',
        'open_inscription',
        'period_id',
        'created_at',
        'updated_at',
    ];
}