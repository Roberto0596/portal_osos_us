<?php 

namespace App\Models\Logs;

use Illuminate\Database\Eloquent\Model;
use App\Models\Logs\Equipment;

class ClassRoom extends Model
{
    protected $table = "classroom";

    //protected $with = ["equipments"];

    public function area() {
        return $this->belongsTo('\App\Models\AdminUsers\Area', "area_id", "id");
    }
    
    public function equipments() {
    	return $this->hasMany("\App\Models\Logs\Equipment", "id", "classroom_id");
    }

    public function getEquipments() {
        $instances = Equipment::where("classroom_id", $this->id)->orderBy("num")->get();
    	return $instances;
    }
}