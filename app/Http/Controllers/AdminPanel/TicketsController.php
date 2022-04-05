<?php

namespace App\Http\Controllers\AdminPanel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PeriodModel;
use App\Models\Alumns\Ticket;
use App\Models\Alumns\User;
use DB;

class TicketsController extends Controller
{
	public function index()
	{
		return view('AdminPanel.tickets.index');
    }


    public function datatable(Request $request) {
    	        $filter = isset($request->get('search')['value']) && $request->get('search')?$request->get('search')['value']:false;
        $start = $request->get('start');
        $length = $request->get('length');
        $columns = $request->get('columns');
        $order = $request->get('order');

        $query = Ticket::leftJoin("users as u", "u.id","=", "ticket.alumn_id")
        				->leftJoin("debit as d", "d.id", "ticket.debit_id")
        				->leftJoin("debit_type as dt", "dt.id", "d.debit_type_id")
        				->leftJoin("period as p", "p.id", "d.period_id")
        				->select("ticket.*", DB::raw("CONCAT_WS(' ', u.name, u.lastname) AS alumnName"), "d.amount as amount", "dt.concept as debit_type", "p.clave");

        if ($request->get('concept') != "all") {
            $query->where("debit_type_id", $request->get('concept'));
        }

        if ($request->get('id_alumno') != "all" && $request->get('id_alumno')) {
        	$user = User::where("id_alumno", $request->get("id_alumno"))->first();

        	if ($user) {
        		$query->where("ticket.alumn_id", $user->id);
        	}
        }

        if ($request->get('period') != "all" && $request->get('period')) {
            $query->where("d.period_id", $request->get('period'));
        }
        
        if ($filter) {
           $query = $query->where(function($query) use ($filter){
                $query->orWhere('concept', 'like', '%'. $filter .'%')
                ->orWhere("u.name", 'like', '%'. $filter .'%')
                ->orWhere("u.lastname", 'like', '%'. $filter .'%')
                ->orWhere("d.period_id", 'like', '%'. $filter .'%');
            });
        } 

        $filtered = $query->count();
        
        $query->skip($start)->take($length)->get();

        if(isset($order) && isset($order[0]) && $order[0]['column'] > -1) {
           $query->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'] );  
        }

        return response()->json([
            "recordsTotal" => Ticket::count(),
            "recordsFiltered" => $filtered,
            "data" => $query->get()
        ]);
    }
}