<?php

namespace App\Http\Controllers\AdminPanel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ConfigModel;
use App\Models\PeriodModel;
use App\Models\Sicoes\Periodo;
use DB;

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
    					$data = Periodo::where("PeriodoId", $value)->first();
    					$period = new PeriodModel();
    					$period->id = $value;
    					$period->clave = $data->Clave;
    					$period->año = $data->Anio;
    					$period->ciclo = $data->Ciclo;
    					$period->semestre = $data->Semestre;
    					$period->save();
    				}

    			} else if($key == "open_inscription") {
                    if ($value != $instance->open_inscription) {
                        DB::table('users')->where("inscripcion", "<>", 0)->update(["inscripcion" => 0]);
                        DB::table('debit')->where([["status", 0],["debit_type_id", 1]])->delete();
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