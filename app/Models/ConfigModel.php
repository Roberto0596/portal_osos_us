<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Sicoes\PlanEstudio;

class ConfigModel extends Model
{

    protected $table = "config";

    protected $fillable = [
        'id',
        'open_inscription',
        'period_id',
        'created_at',
        'updated_at',
        'laep_id',
        'lata_id',
        'in_maintenance'
    ];

    public function period() {
        return $this->belongsTo('\App\Models\PeriodModel', 'period_id', 'id');
    }

    public function getAdministracionData() {
        $data = PlanEstudio::find($this->laep_id);
        return $data;
    }

    public function getTuristmoData() {
        $data = PlanEstudio::find($this->lata_id);
        return $data;
    }
}