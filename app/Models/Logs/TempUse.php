<?php 

namespace App\Models\Logs;

use Illuminate\Database\Eloquent\Model;
use App\Models\Logs\TempUse;

class TempUse extends Model
{
    protected $table = "temp_use";

    protected $with = ["alumn"];

    public function alumn() {
    	return $this->belongsTo("\App\Models\Alumns\User", "alumn_id", "id");
    }

    public function closeUse() {
    	$equipment = Equipment::find($this->equipment_id);
        $equipment->status = 0;
        $equipment->save();

        //insert report
        $this->insertReportRegister([
            "equipment_id" => $equipment->id,
            "alumn_id" => $this->alumn_id,
            "entry_time" => $this->entry_time,
            "area_id" => current_user("log_auth")->area_id
        ]);
        
        $this->delete();
    }

    private function insertReportRegister($array) {
        $report = new ReportEquipment();
        $report->equipment_id = $array["equipment_id"];
        $report->alumn_id = $array["alumn_id"];
        $report->entry_time = $array["entry_time"];
        $report->area_id = $array["area_id"];

        date_default_timezone_set('America/Hermosillo');
        $report->departure_time = date('H:i:s');
        $report->save();
    }
}