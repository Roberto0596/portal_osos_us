<?php

namespace App\Models\Alumns;

use Illuminate\Database\Eloquent\Model;

class Notify extends Model
{
    protected $table = "notify";

    protected $fillable = [
    	'id',
        'text',
        'status',
        'created_at',
        'updated_at',
        'target',
        'id_target'
    ];

    /**
     * crea una nueva notificacion
     */
    public function addNotify($text, $id_target, $target, $route) {
        $this->text = $text;
        $this->id_target = $id_target;
        $this->target = $target;
        $this->route = $route;
        $this->save();
    }
}
