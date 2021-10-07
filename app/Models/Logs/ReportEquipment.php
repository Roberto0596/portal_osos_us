<?php 

namespace App\Models\Logs;

use Illuminate\Database\Eloquent\Model;
use App\Models\Logs\Equipment;
use carbon\Carbon;

class ReportEquipment extends Model
{
    protected $table = "report_equipment";

    protected $appends = ["Date"];

    public function getDateAttribute() {
    	$date = new Carbon($this->created_at);
    	return $date->format('Y-m-d');
    }
}