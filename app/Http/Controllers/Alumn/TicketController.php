<?php

namespace App\Http\Controllers\Alumn;


use App\Models\Alumns\User;
use App\Models\Alumns\Debit;
use Illuminate\Http\Request;
use App\Models\Alumns\Ticket;
use App\Models\Alumns\DebitType;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index()
    {
        return view('Alumn.ticket.index');
    }

    public function show()
    {

        $alumn = Auth::guard('alumn')->user();
        $tickets = Ticket::where("alumn_id","=", $alumn->id)->get();
      

        
        $response = [ "data" => []];       


        foreach($tickets as $key => $value)
        {
            
            $debit = Debit::find($value->debit_id);
            $debitType = DebitType::find($debit->debit_type_id);

            $buttons="<div class='btn-group'>
            <button class='btn btn-warning custom btnPrint' route='".$value->route."'  ticket_id = '".$value->id."' title='Imprimir'><i class='fa fa-print' style='color:white'></i></button></div>
            </div>";
        
            array_push($response["data"],[
                (count($tickets)-($key+1)+1),
                $value->concept,
                "$".number_format($debit->amount,2),
                $debitType->concept,
                $value->created_at == null ? "Sin fecha" : $value->created_at,
                $buttons
            ]);
        }

        return response()->json($response);

    }
}
