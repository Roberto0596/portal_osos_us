<?php

namespace App\Http\Controllers\Alumn;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Alumns\Debit;
use App\Models\Alumns\User;
use App\Models\Alumns\Payment;
use Input;
use Auth;
use DB;

class PaymentController extends Controller
{
    public function index()
    {
        $query = [["id_alumno", current_user()->id_alumno],["status","0"]];
        $debit = Debit::where($query)->get();
        $total = $debit->sum("amount");
        $totalDebitWithOtherConcept = getTotalDebitWithOtherConcept();
        return view('Alumn.payment.index')->with(["debit" => $debit,"total"=>$total, "otherTotal" => $totalDebitWithOtherConcept]);
    }
    
    public function pay_card(Request $request)
    {
        //mandamos llamar a la libreria de conekta
        require_once("conekta/Conekta.php");
        \Conekta\Conekta::setApiKey("key_b6GSXASrcJATTGjgSNxWFg");
        \Conekta\Conekta::setApiVersion("2.0.0");

        //preparamos los datos.
        $tokenId = $request->input("conektaTokenId");
        $current_user = current_user();
        $sicoesAlumn = $current_user->getSicoesData();

        //traer los conceptos que se deben del alumno
        $query = [["id_alumno","=",Auth::guard("alumn")->user()->id_alumno],["status","=","0"]];      
        $debits = Debit::where($query)->get();

        try
        {
          $order = \Conekta\Order::create(
            [
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
            $value->id_order = $order->id;
            $value->payment_method = "card";
            $value->save();
            if ($value->has_file_id != null) {
              $document = Document::find($value->has_file_id);
              $document->payment = 1;
              $document->save();
            }
        }
        
        $register = makeRegister($current_user);

        if (count($register["errors"]) == 0) {
            session()->flash("messages","success|El pago se realizo con exito, ve cual sera tu carga, recuerda que tu correo es: ".$current_user->email);
        } else {
            session()->flash("messages","success|El pago se realizo con exito, pero no se completo la inscripcion, reporta este problema a servicios escolares");
        }
        return redirect()->route("alumn.charge");
    }

    public function pay_cash(Request $request)
    {
        //mandamos llamar a la libreria de conekta
        require_once("conekta/Conekta.php");
        \Conekta\Conekta::setApiKey("key_b6GSXASrcJATTGjgSNxWFg");
        \Conekta\Conekta::setApiVersion("2.0.0");

        //preparamos los datos.
        $current_user = current_user();
        $sicoesAlumn = $current_user->getSicoesData();
        
        //traer los conceptos que se deben del alumno
        $query = [["id_alumno","=",Auth::guard("alumn")->user()->id_alumno],["status","=","0"]];
        $debits = Debit::where($query)->get();

        //crear la orden
        try
        {
            $thirty_days_from_now = (new \DateTime())->add(new \DateInterval('P30D'))->getTimestamp(); 

            $order = \Conekta\Order::create([
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
            $value->payment_method = "oxxo_cash";
            $value->save();
        }

        $current_user->inscripcion = 2;
        $current_user->save();
        
        return redirect()->route("alumn.payment.note");
    }

    public function pay_spei(Request $request)
    { 
        require_once("conekta/Conekta.php");
        \Conekta\Conekta::setApiKey("key_b6GSXASrcJATTGjgSNxWFg");
        \Conekta\Conekta::setApiVersion("2.0.0");

        //preparamos los datos.
        $current_user = current_user();
        $sicoesAlumn = $current_user->getSicoesData();

        //traer los conceptos que se deben del alumno
        $query = [["id_alumno","=",Auth::guard("alumn")->user()->id_alumno],["status","=","0"]];
        $debits = Debit::where($query)->get();
          
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

        $current_user->inscripcion = 2;
        $current_user->save();
        return redirect()->route("alumn.payment.note");
    }

    public function note() 
    {
        $debit = DB::table("debit")->where("id_alumno",Auth::guard("alumn")->user()->id_alumno)->orderby("id","desc")->get();

        $method = $debit[0]->payment_method;

        if($method == "transfer")
        {
            return view('Alumn.payment.others');
        }
        else
        {
            //mandamos llamar a la libreria de conekta
            require_once("conekta/Conekta.php");
            \Conekta\Conekta::setApiKey("key_b6GSXASrcJATTGjgSNxWFg");
            \Conekta\Conekta::setApiVersion("2.0.0");
            $order = \Conekta\Order::find($debit[0]->id_order);
            $total = 0;
            foreach ($order->line_items as $key => $value) 
            {
                $total=$total+$value->unit_price;
            }

            if($method == "oxxo_cash")
            {
                $data = array('total' => $total,
                              'id_order'=>$order->id,
                              'reference'=>$order->charges[0]->payment_method->reference);
                return view('Alumn.payment.oxxo_pay')->with(["order"=>$data]);
            }
            else if($method == "spei")
            {
                $data = array('total' => $total,
                              'id_order'=>$order->id,
                              'reference'=>$order->charges[0]->payment_method->receiving_account_number);
                return view('Alumn.payment.spei_pay')->with(["order"=>$data]);
            }
        }
    }

    public function pay_upload(Request $request)
    {
        $current_user = User::find(Auth::guard("alumn")->user()->id);
        $current_sicoes = getLastSemester($current_user->id_alumno);
        $file = $request->file('file');
        $originalExtention = explode(".", $_FILES["file"]["name"])[1];
        $name = str_replace("_"," ",$current_user->name) . $current_sicoes.".".$originalExtention;

        if(!\Storage::disk('public_uploads')->put($name,  \File::get($file)))
        {
            session()->flash("messages","error|No fue posible guardar el comprobante, intentelo de nuevo");
            return redirect()->back();
        }
        //traer los conceptos que se deben del alumno
        $query = [["id_alumno","=",Auth::guard("alumn")->user()->id_alumno],["status","=","0"]];
        $debits = Debit::where($query)->get();
        $route = "img/comprobantes/".$name;
        
        foreach ($debits as $key => $value)
        {
            $value->id_order = $route;
            $value->payment_method = "transfer";
            $value->save();
        }
        $current_user->inscripcion = 2;
        $current_user->save();
        return redirect()->route("alumn.payment.note");
    }

    public function rollBack(Request $request, $orderId)
    {
        $debits = Debit::where("id_order","=",$orderId)->get();
        if ($debits[0]->cancelled!=1)
        {
            foreach ($debits as $key => $value) 
            {
                $value->cancelled = 1;
                $value->id_order = null;
                $value->save();
            }
            $current_user = Auth::guard("alumn")->user();
            $current_user->inscripcion = 1;
            $current_user->save();
            return redirect()->route("alumn.payment");
        }
        else
        {
            session()->flash("messages","error|Ya cencelaste una vez, contactate con el departamento de finanzas");
            return redirect()->back();
        }
    }
}