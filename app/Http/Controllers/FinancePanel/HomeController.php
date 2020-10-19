<?php

namespace App\Http\Controllers\FinancePanel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Alumns\Debit;
use Auth;
use Input;
use DB;

class HomeController extends Controller
{
	public function index()
	{
        $totalDebit = Debit::where("status", 0)->get()->count();
		return view('FinancePanel.home.index')->with(["debits" => $totalDebit]);
    }


    /*
    
    public function changePaymentStatus(Request $request , $id){
        
        $status = $request->input('status');
        DB::table('debit')
        ->where('id', $id) ->update(['status' => $status]);


        return redirect()->route('finance.home');
    }

    public function showPayementTicket($id_order){

        require_once("conekta/Conekta.php");
        \Conekta\Conekta::setApiKey("key_b6GSXASrcJATTGjgSNxWFg");
        \Conekta\Conekta::setApiVersion("2.0.0");
        $order = \Conekta\Order::find($id_order);
       

        return view('FinancePanel.home.temp');

    }

    */

	

}