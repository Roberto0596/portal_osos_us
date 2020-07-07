<?php

namespace App\Http\Controllers\FinancePanel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Input;
use DB;

class HomeController extends Controller
{
	public function index()
	{

        $debits = DB::table('debit')
        ->join('users', 'users.id_alumno' , '=', 'debit.id_alumno')
        ->select('debit.id','debit.id_order', 'debit.concept' ,'debit.amount','debit.admin_id','debit.status','debit.payment_method','users.name','users.lastname')
        ->get();


		return view('FinancePanel.home.index')->with(['debits'=> $debits]);
    }
    
    public function changePaymentStatus(Request $request , $id){
        
        $status = $request->input('status');
        DB::table('debit')
        ->where('id', $id) ->update(['status' => $status]);
        return redirect()->route('finance.home');
       

        

    }

	public function add() 
	{
    }

    public function edit($id)
    {
    }

    public function delete($id)
    {
    }

    public function save(Request $request) 
    {
    }

}