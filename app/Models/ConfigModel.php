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
        'laep_id',
        'lata_id'
    ];

    public function period() {
        return $this->belongsTo('\App\Models\PeriodModel', 'period_id', 'id');
    }

    public function getAdministracionData() {
        $data = selectSicoes("PlanEstudio","PlanEstudioId", $this->laep_id);

        if ($data) {
            return $data[0];
        } else {
            return null;
        }
    }

    public function getTuristmoData() {
        $data = selectSicoes("PlanEstudio","PlanEstudioId", $this->lata_id);

        if ($data) {
            return $data[0];
        } else {
            return null;
        }
    }
}