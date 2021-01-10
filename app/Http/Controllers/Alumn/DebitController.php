<?php

namespace App\Http\Controllers\Alumn;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Alumns\User;
use App\Models\Alumns\Document;
use App\Models\Alumns\Debit;
use App\Models\AdminUsers\AdminUser;
use Illuminate\Support\Collection;
use Input;
use Auth;

class DebitController extends Controller
{
  	public function index()
  	{
        $query = [["id_alumno","=", current_user()->id_alumno],["status","=","0"], ["debit_type_id","<>", 1]];
        $debits = Debit::where($query)->get();
          return view('Alumn.debit.index')->with(["debits" => $debits]);
  	}

    public function payCard(Request $request) {
      
        $debitId = json_decode($request->get("debitList"),true);
        if (count($debitId) == 0) {
            session()->flash("messages","info|Asegurece de elegir algunos adeudos");
            return redirect()->back();
        }

        //mandamos llamar a la libreria de conekta
        require_once("conekta/Conekta.php");
        \Conekta\Conekta::setApiKey("key_b6GSXASrcJATTGjgSNxWFg");
        \Conekta\Conekta::setApiVersion("2.0.0");

        //preparamos los datos.
        $tokenId = $request->input("conektaTokenId");
        $current_user = current_user();
        $sicoesAlumn = $current_user->getSicoesData();

        //traer los conceptos que se deben del alumno
        $debits = getDebitByArray($debitId);

        try
        {
            $order = \Conekta\Order::create([
                "line_items" => getArrayItem($debits, "card"),
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
            ]);
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
            $value->id_order = $order->id;
            $value->payment_method = "card";
            $value->save();
            if ($value->has_file_id != null) {
              $document = Document::find($value->has_file_id);
              $document->payment = 1;
              $document->save();
            }
            try {
                createTicket($value->id);
            } catch(\Exception $e){

            }
        }

        session()->flash("message-2","success|Su pago ha sido realizado con exito, muchas gracias");
        return redirect()->back();
    }

    public function payOxxo(Request $request) {

        $debitId = json_decode($request->get("debitList"),true);
        if (count($debitId) == 0) {
            session()->flash("messages","info|Asegurece de elegir algunos adeudos");
            return redirect()->back();
        }

        //mandamos llamar a la libreria de conekta
        require_once("conekta/Conekta.php");
        \Conekta\Conekta::setApiKey("key_b6GSXASrcJATTGjgSNxWFg");
        \Conekta\Conekta::setApiVersion("2.0.0");

        //preparamos los datos.
        $current_user = current_user();
        $sicoesAlumn = $current_user->getSicoesData();

        $debits = getDebitByArray($debitId);
        
        //crear la orden
        try
        {
            $thirty_days_from_now = (new \DateTime())->add(new \DateInterval('P30D'))->getTimestamp(); 

            $order = \Conekta\Order::create(
              [
                "line_items" => getArrayItem($debits, "oxxo"),
                "currency" => "MXN",
                "customer_info" => [
                  "name" => $current_user->name,
                  "email" => $current_user->email,
                  "phone" => $sicoesAlumn["Telefono"]!=null?$sicoesAlumn["Telefono"]:"1234234321"
                ],
                "metadata" => ["Matricula" => $sicoesAlumn["Matricula"]],
                "charges" => [
                  [
                    "payment_method" => [
                      "type" => "oxxo_cash",
                      "expires_at" => $thirty_days_from_now
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
            $value->id_order = $order->id;
            $value->payment_method = "oxxo_cash";
            $value->save();
        }

        session()->flash("message-2","success|Su metodo de pago OXXO fue ingresado con exito, para ver el numero de referencia, por favor vaya al adeudo y oprima el voton verde. Una vez validado por el departamento de finanzas el adeudo ya no aparecera");
        return redirect()->back();    
    }

    public function paySpei(Request $request) {
        
        $debitId = json_decode($request->get("debitList"),true);
        if (count($debitId) == 0) {
            session()->flash("messages","info|Asegurece de elegir algunos adeudos");
            return redirect()->back();
        }

        //mandamos llamar a la libreria de conekta
        require_once("conekta/Conekta.php");
        \Conekta\Conekta::setApiKey("key_b6GSXASrcJATTGjgSNxWFg");
        \Conekta\Conekta::setApiVersion("2.0.0");

        //preparamos los datos.
        $current_user = current_user();
        $sicoesAlumn = $current_user->getSicoesData();

        $debits = getDebitByArray($debitId);
        
        //crear la orden
        try
        {
            $thirty_days_from_now = (new \DateTime())->add(new \DateInterval('P30D'))->getTimestamp(); 

            $order = \Conekta\Order::create([
                "line_items" => getArrayItem($debits, "spei"),
                "currency" => "MXN",
                "customer_info" => [
                  "name" => $current_user->name,
                  "email" => $current_user->email,
                  "phone" => $sicoesAlumn["Telefono"]!=null?$sicoesAlumn["Telefono"]:"1234234321"
                ],
                "metadata" => ["Matricula" => $sicoesAlumn["Matricula"]],
                "charges" => [
                  [
                    "payment_method" => [
                      "type" => "spei",
                      "expires_at" => $thirty_days_from_now
                    ]
                  ]
                ]
            ]);
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
            $value->id_order = $order->id;
            $value->payment_method = "spei";
            $value->save();
        }

        session()->flash("message-2","success|Su metodo de pago SPEI fue ingresado con exito, para ver la clave, por favor vaya al adeudo y oprima el voton verde. Una vez validado por el departamento de finanzas el adeudo ya no aparecera");
        return redirect()->back(); 
    }

    public function note($id_order) {
        $debit = Debit::where("id_order", $id_order)->get();
        require_once("conekta/Conekta.php");
        \Conekta\Conekta::setApiKey("key_b6GSXASrcJATTGjgSNxWFg");
        \Conekta\Conekta::setApiVersion("2.0.0");
        $order = \Conekta\Order::find($debit[0]->id_order);
        $total = 0;
        foreach ($order->line_items as $key => $value) 
        {
            $total=$total+$value->unit_price;
        }

        if($debit[0]->payment_method == "oxxo_cash")
        {
            $data = array('total' => $total,
                            'id_order'=>$order->id,
                            'reference'=>$order->charges[0]->payment_method->reference);
            return view('Alumn.payment.oxxo_pay')->with(["order"=>$data, "inscripcionNo" => true]);
        }
        else if($debit[0]->payment_method == "spei")
        {
            $data = array('total' => $total,
                            'id_order'=>$order->id,
                            'reference'=>$order->charges[0]->payment_method->receiving_account_number);
            return view('Alumn.payment.spei_pay')->with(["order"=>$data, "inscripcionNo" => true]);
        }
    }

    public function pay_upload(Request $request)
    {
        $debitId = json_decode($request->get("debitList"),true);

        if (count($debitId) == 0) {
            session()->flash("messages","info|Asegurece de elegir algunos adeudos");
            return redirect()->back();
        }

        //preparamos los datos.
        $current_user = current_user();
        $sicoesAlumn = $current_user->getSicoesData();

        $debits = getDebitByArray($debitId);

        $file = $request->file('file');

        $name =  uniqid().".".$file->getClientOriginalExtension();
        $path =  'img/comprobantes/';
        try{
            $file->move($path, $name);
        } catch(\Exception $e) {
           session()->flash("messages","error|No fue posible guardar el comprobante, intentelo de nuevo");
            return redirect()->back(); 
        }

        foreach ($debits as $key => $value)
        {
            $value->id_order = $path.$name;
            $value->payment_method = "transfer";
            $value->save();
        }
        return redirect()->back();
    }

}