<?php

namespace App\Http\Controllers\Alumn;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Alumns\Debit;
use App\Models\Alumns\User;
use App\Models\Alumns\Payment;
use Input;
use Auth;
use Illuminate\Support\Collection;

class PaymentController extends Controller
{
	public function index()
	{
        $query = [["id_alumno","=",Auth::guard("alumn")->user()->id_alumno],["status","=","0"]];
        $debit = Debit::where($query)->get();
        $total = $debit->sum("amount");
        if (!session()->has("debits"))
        {
            session(["debits"=>$debit]);
        }
		return view('Alumn.payment.index')->with(["debit" => $debit,"total"=>$total]);
	}

    public function pay_card(Request $request)
    {
        $debits = session("debits");
        foreach ($debits as $key => $value)
        {
            $value->status = 1;
            $value->save();
        }
        $current_user = User::find(Auth::guard("alumn")->user()->id);
        $current_user->inscripcion = 2;
        $current_user->save();
        return redirect()->route("alumn.charge");
    }
    public function edit($id)
    {
    }

    public function delete($id)
    {
    }
}