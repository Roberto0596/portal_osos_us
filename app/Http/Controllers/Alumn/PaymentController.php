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
		return view('Alumn.payment.index')->with(["debit" => $debit,"total"=>$total]);
	}

    public function pay_card(Request $request)
    {
        //mandamos llamar a la libreria de conekta
        require_once("conekta/Conekta.php");
        \Conekta\Conekta::setApiKey("key_b6GSXASrcJATTGjgSNxWFg");
        \Conekta\Conekta::setApiVersion("2.0.0");

        //preparamos los datos.
        $tokenId = $request->input("conektaTokenId");
        $current_user = User::find(Auth::guard("alumn")->user()->id);
        $sicoesAlumn = selectSicoes("alumno","alumnoid",$current_user->id_alumno)[0];

        //traer los conceptos que se deben del alumno
        $query = [["id_alumno","=",Auth::guard("alumn")->user()->id_alumno],["status","=","0"]];
        $debits = Debit::where($query)->get();

        $item_array = [];

        foreach ($debits as $key => $value)
        {
            $items = array('name' => $value->concept,
                            "unit_price" => $value->amount,
                            "quantity" => 1);
            array_push($item_array, $items);
        }

        try
        {
          $order = \Conekta\Order::create(
            [
              "line_items" => $item_array,
              "currency" => "MXN",
              "customer_info" => [
                "name" => $current_user->name,
                "email" => $current_user->email,
                "phone" => $sicoesAlumn["Telefono"]!=null?$sicoesAlumn["Telefono"]:"1234234321"
              ],
              "metadata" => ["Matricula" => $sicoesAlumn["Matricula"]],
              "charges" => [
                [
                  "payment_method" => 
                  [
                    "type" => "card",
                    "token_id" => $tokenId,
                  ] 
                ]
              ]
            ]
          );
        } 
        catch (\Conekta\ProcessingError $error)
        {
            session()->flash("messages","error|".$error->getMessage());
            return redirect()->back();
        } 
        catch (\Conekta\ParameterValidationError $error)
        {
            session()->flash("messages","error|".$error->getMessage());
            return redirect()->back();
        }
        catch (\Conekta\Handler $error)
        {
            session()->flash("messages","error|".$error->getMessage());
            return redirect()->back();
        }
        
        foreach ($debits as $key => $value)
        {
            $value->status = 1;
            $value->save();
        }
        $current_user->inscripcion = 2;
        $current_user->save();
        return redirect()->route("alumn.charge");
    }

    public function form_payment()
    {
        return view('Alumn.payment.card'); 
    }

    public function delete($id)
    {
    }
}