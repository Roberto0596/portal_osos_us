<?php 

namespace App\Models\Logs;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    protected $table = "equipment";

    public function classroom() {
    	return $this->belongsTo("\App\Models\Logs\ClassRoom", "classroom_id", "id");
    }

    public function tempUse() {
    	return $this->hasOne("\App\Models\Logs\TempUse", "equipment_id", "id");
    }

    public function reserve($args) {

        if ($this->status == 1) {
            throw new Exception("esta computadora ya está reservada o está fuera de servicio");
        }

        $instance = new TempUse();
        $instance->equipment_id = $this->id;
        $instance->alumn_id = $args["id"];
        $instance->enrollment = $args["enrollment"];
        $instance->entry_time = $args['time'];
        $instance->area_id = $args["area_id"];
        $instance->save();
        $this->status = 1;
        $this->save();
    }

    public function image() {
    	switch ($this->status) {
    		case 0:
    			return asset("img/log/log.libre.png");
    			break;    		
    		case 1:
    			return asset("img/log/log.ocupada.png");
    			break;
    		case 2:
    			return asset("img/log/log.mantenimiento.png");
    			break;
            default:
                return asset("img/log/log.mantenimiento.png");
                break;
    	}
    }
}