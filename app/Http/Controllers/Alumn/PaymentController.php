<?php

namespace App\Http\Controllers\Alumn;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Alumns\Debit;
use App\Models\Alumns\User;
use App\Models\Alumns\Payment;
use App\Library\Inscription;
use App\Enum\DebitStatus;
use Input;
use Auth;
use DB;

class PaymentController extends Controller
{
    private $current_user;

    public function callAction($method, $parameters)
    {
        $this->current_user = current_user();
        return parent::callAction($method, $parameters);
    }

    public function index()
    {
        $query = Debit::where("id_alumno", $this->current_user->id_alumno)
                        ->where("status", 0)
                        ->get();
        $total = $query->sum("amount");
        $totalDebitWithOtherConcept = getTotalDebitWithOtherConcept();
        return view('Alumn.payment.index')->with([
            "debit" => $query,
            "total" => $total, 
            "otherTotal" => $totalDebitWithOtherConcept
        ]);
    }
    
    public function pay_card(Request $request)
    {
        $tokenId = $request->input("conektaTokenId");

        $sicoesAlumn = $this->current_user->sAlumn;

        $debits = Debit::where("id_alumno", $this->current_user->id_alumno)
                        ->where("status", 0)
                        ->get();

        $order = $this->createOrder([
                "line_items" => getArrayItem($debits, "card"),
                "currency" => "MXN",
                "customer_info" => [
                    "name" => $this->current_user->name,
                    "email" => $this->current_user->email,
                    "phone" => $sicoesAlumn->Telefono != null ? $sicoesAlumn->Telefono : "1234234321"
                ],
                "metadata" => ["Matricula" => $sicoesAlumn->Matricula],
                "charges" => [
                    [
                        "payment_method" => [
                            "type" => "card",
                            "token_id" => $tokenId,
                        ] 
                    ]
                ]
        ]);

        if (!$order) {
            session()->flash("messages", "error|Ocurrió un problema durante el pago");
            return redirect()->back();
        }

        foreach ($debits as $key => $value) {
            $value->validate(Debit::getStatus(DebitStatus::paid()), "card", $order->id);
        }
        
        $register = Inscription::makeRegister($this->current_user);

        if ($register->status == "success") {
            session()->flash("messages","success|El pago se realizo con exito, ve cual sera tu carga, recuerda que tu correo es: ".$this->current_user->email);
        } else {
            session()->flash("messages","success|El pago se realizo con exito, pero no se completo la inscripcion, reporta este problema a servicios escolares");
        }
        return redirect()->route("alumn.charge");
    }

    public function pay_cash(Request $request)
    {
        $sicoesAlumn = $this->current_user->sAlumn;

        $debits = Debit::where("id_alumno", $this->current_user->id_alumno)
                        ->where("status", 0)
                        ->get();
        
        $thirty_days_from_now = (new \DateTime())->add(new \DateInterval('P30D'))->getTimestamp(); 
        $order = $this->createOrder([
            "line_items" => getArrayItem($debits, "oxxo"),
            "currency" => "MXN",
            "customer_info" => [
                "name" => $this->current_user->name,
                "email" => $this->current_user->email,
                "phone" => $sicoesAlumn->Telefono != null ? $sicoesAlumn->Telefono : "1234234321"
            ],
            "metadata" => ["Matricula" => $sicoesAlumn->Matricula],
            "charges" => [
                [
                    "payment_method" => [
                        "type" => "oxxo_cash",
                        "expires_at" => $thirty_days_from_now
                    ]
                ]
            ]
        ]);

        if (!$order) {
            session()->flash("messages", "error|Ocurrió un problema durante el pago");
            return redirect()->back();
        }

        foreach ($debits as $key => $value) {
            $value->validate(Debit::getStatus(DebitStatus::pending()), "oxxo_cash", $order->id);
        }

        $this->current_user->nextStep();
        
        return redirect()->route("alumn.payment.note");
    }

    public function pay_spei(Request $request)
    { 
        $sicoesAlumn = $this->current_user->sAlumn;

        $debits = Debit::where("id_alumno", $this->current_user->id_alumno)
                        ->where("status", 0)
                        ->get();
          
        $thirty_days_from_now = (new \DateTime())->add(new \DateInterval('P30D'))->getTimestamp();
        $order = $this->createOrder([
            "line_items" => getArrayItem($debits, "spei"),
            "currency" => "MXN",
            "customer_info" => [
                "name" => $this->current_user->name,
                "email" => $this->current_user->email,
                "phone" => $sicoesAlumn->Telefono != null ? $sicoesAlumn->Telefono : "1234234321"
            ],
            "metadata" => ["Matricula" => $sicoesAlumn->Matricula],
            "charges" => [
                [
                    "payment_method" => [
                      "type" => "spei",
                      "expires_at" => $thirty_days_from_now
                    ]
                ]
            ]
        ]);

        if (!$order) {
            session()->flash("messages", "error|Ocurrió un problema durante el pago");
            return redirect()->back();
        }

        foreach ($debits as $key => $value) {
            $value->validate(Debit::getStatus(DebitStatus::pending()), "spei", $order->id);
        }

        $this->current_user->nextStep();

        return redirect()->route("alumn.payment.note");
    }

    public function note() 
    {
        $debit = Debit::where("id_alumno", $this->current_user->id_alumno)
                        ->orderby("id","desc")
                        ->get();

        $method = $debit[0]->payment_method;

        if($method == "transfer") {
            return view('Alumn.payment.others');
        } else  {
            require_once("conekta/Conekta.php");
            \Conekta\Conekta::setApiKey("key_b6GSXASrcJATTGjgSNxWFg");
            \Conekta\Conekta::setApiVersion("2.0.0");
            $order = \Conekta\Order::find($debit[0]->id_order);

            $total = 0;
            foreach ($order->line_items as $key => $value) {
                $total = $total + $value->unit_price;
            }

            if($method == "oxxo_cash") {
                return view('Alumn.payment.oxxo_pay')->with([
                    "order"=> [
                        'total' => $total,
                        'id_order' => $order->id,
                        'reference' => $order->charges[0]->payment_method->reference
                    ]
                ]);
            } else if($method == "spei") {
                return view('Alumn.payment.spei_pay')->with([
                    "order" => [
                        'total' => $total,
                        'id_order' => $order->id,
                        'reference' => $order->charges[0]->payment_method->receiving_account_number
                    ]
                ]);
            }
        }
    }

    public function pay_upload(Request $request)
    {
        $sicoesAlumn = $this->current_user->sAlumn;

        $debits = Debit::where("id_alumno", $this->current_user->id_alumno)
                        ->where("status", 0)
                        ->get();

        $file = $request->file('file');

        $name = uniqid().".".$file->getClientOriginalExtension();
        $path = 'img/comprobantes/';

        try {
            $file->move($path, $name);
        } catch(\Exception $e) {
           session()->flash("messages","error|No fue posible guardar el comprobante, intentelo de nuevo");
            return redirect()->back(); 
        }

        foreach ($debits as $key => $value) {
            $value->validate(0, "transfer", $path.$name);
        }

        $this->current_user->nextStep();

        return redirect()->route("alumn.payment.note");
    }

    public function rollBack(Request $request, $orderId)
    {
        $debits = Debit::where("id_order","=",$orderId)->get();

        if ($debits[0]->cancelled != 1) {
            foreach ($debits as $key => $value) {
                $value->cancelled = 1;
                $value->id_order = null;
                $value->save();
            }

            $this->current_user->inscripcion = 1;
            $this->current_user->save();
            return redirect()->route("alumn.payment");
        } else {
            session()->flash("messages","error|Ya cencelaste una vez, contactate con el departamento de finanzas");
            return redirect()->back();
        }
    }

    private function createOrder($set) {
        require_once("conekta/Conekta.php");
        \Conekta\Conekta::setApiKey("key_b6GSXASrcJATTGjgSNxWFg");
        \Conekta\Conekta::setApiVersion("2.0.0");
        try {
            $order = \Conekta\Order::create($set);
            return $order;
        } catch (\Conekta\ProcessingError $error) {
            return null;
        } catch (\Conekta\ParameterValidationError $error) {
            return null;
        } catch (\Conekta\Handler $error) {
            return null;
        }
    }
}