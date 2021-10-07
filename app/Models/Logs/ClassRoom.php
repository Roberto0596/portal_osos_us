<?php 

namespace App\Models\Logs;

use Illuminate\Database\Eloquent\Model;
use App\Models\Logs\Equipment;

class ClassRoom extends Model
{
    protected $table = "classroom";

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

    public function equipmentStatus() {
        $query = ClassRoom::leftJoin("equipment as e", "e.classroom_id", "=", "classroom.id")
                ->where("classroom.area_id", $this->area_id);
        return (Object) [
            "used" => $query->where("e.status", 1)->count(),
            "free" => $query->where("e.status", 0)->count(),
            "total" => $query->count()
        ];
    }
}