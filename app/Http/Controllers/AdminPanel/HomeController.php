<?php

namespace App\Http\Controllers\AdminPanel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PeriodModel;

class HomeController extends Controller
{
	public function index()
	{
		$period = PeriodModel::all();
		return view('AdminPanel.home.index')->with(["period" => $period[0]]);
    }

    public function savePeriod(Request $request, PeriodModel $period)
	{
		try
		{
			$period_sicoes = selectSicoes("Periodo","PeriodoId",$request->input("period"))[0];
			$period->id = $period_sicoes["PeriodoId"];
			$period->clave = $period_sicoes["Clave"];
			$period->año = $period_sicoes["Anio"];
			$period->ciclo = $period_sicoes["Ciclo"];
			$period->semestre = $period_sicoes["Semestre"];
			$period->save();
			session()->flash("messages","success|Se guardo correctamente");
		    return redirect()->back();
		}
		catch(\Exception $e)
		{
			session()->flash("messages","error|Algo salio mal");
			return redirect()->back();
		}
    }
}