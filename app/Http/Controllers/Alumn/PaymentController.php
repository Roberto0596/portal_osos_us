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
                          "unit_price" => $value->amount*100,
                          "quantity" => 1);
          array_push($item_array, $items);
      }

      //agregamos la comision bancaria correspondiente.
      $commission = array('name' => 'comision bancaria',
                          'unit_price' => floatval((70.89*100)),
                          'quantity'=>1);
      array_push($item_array, $commission);

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
          $value->id_order = $order->id;
          $value->payment_method = "card";
          $value->save();
      }

      $inscripcionData = getLastThing("Inscripcion","AlumnoId",$current_user->id_alumno,"InscripcionId");

      //verificamos que es un alumno nuevo y no se esta inscribiendo
      if (!$inscripcionData)
      {
        //traemos la matricula para el alumno que acaba de pagar
        $enrollement = generateCarnet($sicoesAlumn["PlanEstudioId"]);
        updateByIdAlumn($current_user->id_alumno,"Matricula",$enrollement);
        $current_user->email = "a".str_replace("-","",$enrollement)."@unisierra.edu.mx";
        $semester = 1;
      } 
      else
      {
        $semester = $inscripcionData["Semestre"]+1;
      }   

      //inscribimos al alumno despues de pagar
      $inscription = array('Semestre' => $semester,'EncGrupoId'=> 14466,'Fecha'=> getDateCustom(),'Baja'=>0, 'AlumnoId'=>$current_user->id_alumno);
      try
      {
        if (inscribirAlumno($inscription))
        {
          $current_user->inscripcion = 3;
          $current_user->save();
          session()->flash("messages","success|El pago se realizo con exito, ve cual sera tu carga, recuerda que tu correo es: ".$current_user->email);
          return redirect()->route("alumn.charge");
        }
        else
        {
          $current_user->inscripcion = 3;
          $current_user->save();
          session()->flash("messages","info|No pudimos inscribirte, pero no te preocupes, tu registro esta intacto solo debes notificar sobre este fallo");
          return redirect()->route("alumn.charge");
        }

        //generamos los documentos de inscripcion
        insertInscriptionDocuments($current_user->id);
      }
      catch(\Exception $e)
      {
        $current_user->inscripcion = 4;
        $current_user->save();
        session()->flash("messages","info|No pudimos inscribirte, pero no te preocupes, tu registro esta intacto solo debes notificar sobre este fallo");
        return redirect()->route("alumn.charge");
      }
  }

  public function pay_cash(Request $request)
  {
      //mandamos llamar a la libreria de conekta
      require_once("conekta/Conekta.php");
      \Conekta\Conekta::setApiKey("key_b6GSXASrcJATTGjgSNxWFg");
      \Conekta\Conekta::setApiVersion("2.0.0");

      //preparamos los datos.
      $tokenId = $request->input("conektaTokenId");
      $current_user = Auth::guard("alumn")->user();
      $sicoesAlumn = selectSicoes("alumno","alumnoid",$current_user->id_alumno)[0];

      //traer los conceptos que se deben del alumno
      $query = [["id_alumno","=",Auth::guard("alumn")->user()->id_alumno],["status","=","0"]];
      $debits = Debit::where($query)->get();

      $item_array = [];

      foreach ($debits as $key => $value)
      {
          $items = array('name' => getDebitType($value->debit_type_id)->concept,
                          "unit_price" => $value->amount*100,
                          "quantity" => 1);
          array_push($item_array, $items);
      }

      //agregamos la comision bancaria correspondiente.
      $commission = array('name' => 'comision bancaria',
                          'unit_price' => floatval((92.39*100)),
                          'quantity'=>1);

      array_push($item_array, $commission);

      try
      {
        $thirty_days_from_now = (new \DateTime())->add(new \DateInterval('P30D'))->getTimestamp(); 

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

      $current_user->inscripcion = 2;
      $current_user->save();
      
      return redirect()->route("alumn.payment.note");
  }

  public function pay_spei(Request $request)
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
                          "unit_price" => $value->amount*100,
                          "quantity" => 1);
          array_push($item_array, $items);
      }

      //agregamos la comision bancaria correspondiente.
      $commission = array('name' => 'comision bancaria',
                          'unit_price' => floatval((14.5*100)),
                          'quantity'=>1);
      array_push($item_array, $commission);

      //crear la orden
      try
      {
        $thirty_days_from_now = (new \DateTime())->add(new \DateInterval('P30D'))->getTimestamp(); 

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
                "payment_method" => [
                  "type" => "spei",
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
        $totalAndReference = array('total' => $total,
                                'reference'=>$order->charges[0]->payment_method->reference);
        return view('Alumn.payment.oxxo_pay')->with(["order"=>$totalAndReference]);
      }
      else if($method == "spei")
      {
        $totalAndReference = array('total' => $total,
                                'reference'=>$order->charges[0]->payment_method->receiving_account_number);
        return view('Alumn.payment.spei_pay')->with(["order"=>$totalAndReference]);
      }
    }
  }

  public function pay_upload(Request $request)
  {
    $current_user = User::find(Auth::guard("alumn")->user()->id);
    $current_sicoes = getLastSemester($current_user->id_alumno);
    $file = $request->file('file');
    $name = str_replace("_"," ",$current_user->name) . $current_sicoes.".pdf";

    if(!\Storage::disk('public_uploads')->put($name,  \File::get($file)))
    {
        session()->flash("messages","error|No fue posible guardar el comprobante, intentelo de nuevo");
        return redirect()->back();
    }
    //traer los conceptos que se deben del alumno
    $query = [["id_alumno","=",Auth::guard("alumn")->user()->id_alumno],["status","=","0"]];
    $debits = Debit::where($query)->get();
    $route = "img/comprobantes/".$name;        //aquiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii
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
}