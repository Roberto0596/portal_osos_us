<?php

namespace App\Http\Controllers\AdminPanel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ConfigModel;
use App\Models\PeriodModel;

class SettingsController extends Controller
{
	public function index()
	{
		$config = ConfigModel::first();

		if (!$config) {
			$config = new ConfigModel();
		}
		return view('AdminPanel.settings.index')->with(["instance" => $config]);
    }


    public function save(Request $request, ConfigModel $instance) {

    	$data = $request->except("_token");

    	foreach ($data as $key => $value) {
    		if ($value != null) {
    			if ($key == 'period_id') {
    				$period = PeriodModel::find($value);

    				if (!$period) {
    					$data = selectSicoes("Periodo","PeriodoId",$value)[0];
    					$period = new PeriodModel();
    					$period->id = $value;
    					$period->clave = $data["Clave"];
    					$period->año = $data["Anio"];
    					$period->ciclo = $data["Ciclo"];
    					$period->semestre = $data["Semestre"];
    					$period->save();
    				}
    			}

    			$instance->$key = $value;
    		}
    	}

    	$instance->updated_at = getDateCustom();
    	$instance->save();

    	session()->flash("messages","info|Guardado con exito");
    	return redirect()->back();
    }
}