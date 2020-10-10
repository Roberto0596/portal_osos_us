<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodModel extends Model
{

    protected $table = "period";

    protected $fillable = [
        'id',
        'clave',
        'año',
        'ciclo',
        'semestre',
        'created_at',
        'updated_at',
    ];

    public function config() {
        return $this->hasOne('\App\Models\ConfigModel', 'id', 'period_id');
    }
}