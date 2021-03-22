<?php 

namespace App\Models\Logs;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    protected $table = "equipment";

    public function classroom() {
    	return $this->belongsTo("\App\Models\Logs\ClassRoom", "classroom_id", "id");
    }

}