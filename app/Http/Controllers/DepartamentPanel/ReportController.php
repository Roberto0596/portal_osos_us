<?php namespace App\Http\Controllers\DepartamentPanel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Logs\Equipment;
use App\Models\Logs\TempUse;
use App\Models\Logs\ClassRoom;
use App\Models\Logs\ReportEquipment;
use App\Models\Sicoes\Alumno;
use App\Models\Alumns\User;
use Carbon\Carbon;
use DB;

class ReportController extends Controller {

	public function index() {
		return view("DepartamentPanel.logs.report.index");
	}

	public function datatable(Request $request) {
        $filter = isset($request->get('search')['value']) && $request->get('search')  ?$request->get('search')['value']:false;

        $start = $request->get('start');
        $length = $request->get('length');
        $filtered = 0;

        $query = ReportEquipment::select([
        	"report_equipment.*",
        	DB::raw("CONCAT_WS(' ', u.name, u.lastname) AS full_name"),
            "u.enrollment",
        	"e.code as equipment",
        	"c.name as classroom",
        	"a.name as area"
        ])->leftJoin("users as u", "report_equipment.alumn_id", "u.id")
        ->leftJoin("equipment as e", "report_equipment.equipment_id", "e.id")
        ->leftJoin("classroom as c", "e.classroom_id", "c.id")
        ->leftJoin("area as a", "c.area_id", "a.id")
        ->where("a.id", current_user('departament')->area_id);

        if ($request->has('initDate') && $request->get('initDate') != "" && $request->get('initDate')) {
            $init = $request->get('initDate');
            $end = $request->get('endDate');

            if ($init == $end) {
                $query = $query->where("report_equipment.created_at", "like", $init."%");
            } else {
                $end = new Carbon($end);
                $end = $end->addDays(1)->format('Y-m-d');
                $query = $query->whereBetween("report_equipment.created_at", [$init, $end]);
            }
        }
        
        if ($filter) {
           $query = $query->where(function($query) use ($filter){
                $query->orWhere('report_equipment.entry', 'like', '%'. $filter .'%')
                ->orWhere('u.name', 'like', '%'. $filter .'%')
                ->orWhere('u.lastname', 'like', '%'. $filter .'%')
                ->orWhere('e.code', 'like', '%'. $filter .'%')
                ->orWhere('c.name', 'like', '%'. $filter .'%')
                ->orWhere('a.name', 'like', '%'. $filter .'%');
            });
        } 

        $filtered = $query->count();

        $query->skip($start)->take($length)->get();

        if(isset($order) && isset($order[0]) && $order[0]['column'] > -1) {
           $query->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'] );  
        }

        return response()->json([
            "recordsTotal" => ReportEquipment::count(),
            "recordsFiltered" => $filtered,
            "data" => $query->get()
        ]);
	}
}