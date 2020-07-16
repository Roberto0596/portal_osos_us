<?php

namespace App\Http\Controllers\FinancePanel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Alumns\Debit;
use App\Models\Alumns\User;
use Input;
use Auth;

class DebitController extends Controller
{
    public function index()
	{
		return view('FinancePanel.debit.index');
    }

    //le agregamos los botones a la tabla y los datos
    public function showDebit(Request $request)
    {
        $current_user = Auth::guard("finance")->user();
        $res = [ "data" => []];
        $debits = Debit::all();

        foreach($debits as $key => $value)
        {

            $buttons="<div class='btn-group'>";

            $buttons.="
            <button class='btn btn-danger custom edit' data-toggle='modal' data-target='#modalEdit' DebitId='".$value->id."' title='Editar Adeudo'>
            <i class='fa fa-pen' style='color:white'></i></button>
            ";

            if ($value->payment_method == 'transfer') 
            {
                $buttons.="
                    <button class='btn btn-success showPdf' route='".$value->id_order."''><i class='fa fa-file' title='Ver detalles del pago' style='color:white'></i></button>";
            }

            if ($value->status == 1 && $value->payment_method != 'transfer') 
            {
                $buttons.="
                    <button class='btn btn-success details' data-toggle='modal' data-target='#modalShowDetails' is='".$value->payment_method."' DebitId='".$value->id."'>
                    <i class='fa fa-file' title='Ver detalles del pago' style='color:white'></i></button>";
            }

            $buttons.="</div>";            

            $alumn = selectSicoes("Alumno","AlumnoId",$value->id_alumno)[0];
            array_push($res["data"],[
                (count($debits)-($key+1)+1),
                $value->concept,
                "$".number_format($value->amount,2),
                $current_user->name,
                $alumn["Nombre"]." ".$alumn["ApellidoPrimero"],
                ($value->status==1)?"Pagada":"Pendiente",
                substr($value->created_at,0,11),
                $buttons
            ]);
        }

        return response()->json($res);
    }

    //este metodo lo usamos con ajax para cargar los datos del adeudo para despues pasarlos al modal
	public function seeDebit(Request $request) 
	{
       
        $debit = Debit::find($request->input("DebitId"));
        $alumn = selectSicoes("Alumno","AlumnoId",$debit->id_alumno)[0];
        $data = array(
        "concept"   =>$debit->concept,
        "alumnName" =>$alumn["Nombre"].$alumn["ApellidoPrimero"],
        "amount"    =>$debit->amount,
        "debitId"   => $debit->id,
        "alumnId" => $alumn['AlumnoId'],
        "status"    => $debit->status
                    
        );
        return response()->json($data);
    }

   
    // sirve para editar un adeudo 
    public function update(Request $request)
    {
        $array = $request->input();
        try 
        {
            $debit = Debit::find($request->input("DebitId"));
            if (array_key_exists("EditStatus", $array))
            {
                $debit->id_alumno = $request->input("EditId_alumno");
                $debit->status    = $request->input("EditStatus");

                if ($array["concept"] == "Pago de colegiatura") 
                {
                    $alumn = User::where("id_alumno","=",$debit->id_alumno)->get()[0];
                    $inscripcionData = getLastThing("Inscripcion","AlumnoId",$alumn->id_alumno,"InscripcionId");

                      //verificamos que es un alumno nuevo y no se esta inscribiendo
                      if (!$inscripcionData)
                      {
                        //traemos la matricula para el alumno que acaba de pagar
                        $sicoesAlumn = selectSicoes("Alumno","AlumnoId",$alumn->id_alumno)[0];
                        $enrollement = generateCarnet($sicoesAlumn["PlanEstudioId"]);
                        updateByIdAlumn($alumn->id_alumno,"Matricula",$enrollement);
                        $alumn->email = "a".str_replace("-","",$enrollement)."@unisierra.edu.mx";
                        $semester = 1;
                      } 
                      else
                      {
                        $semester = $inscripcionData["Semestre"]+1;
                      }   

                      //inscribimos al alumno despues de pagar
                      $inscription = array('Semestre' => $semester,'EncGrupoId'=> 14466,'Fecha'=> getDateCustom(),'Baja'=>0, 'AlumnoId'=>$alumn->id_alumno);
                      $inscribir = inscribirAlumno($inscription);
                      $alumn->inscripcion=3;
                      $alumn->save();
                }

            }
            $debit->concept   = $request->input("concept");
            $debit->amount    = $request->input("amount");
            $debit->save();
            session()->flash("messages","success|Se guardó correctamente");
            return redirect()->back();
        } 
        catch (\Exception $th) 
        {
            session()->flash("messages","error|No pudimos guardar los datos");
            return redirect()->back();
        }        
    }

    //creamos un nuevo adeudos y se guarda el taba de debits
    public function save(Request $request) 
    {
        $request->validate([
            'concept' => 'required',
            'amount' => 'required',
            'id_alumno'=>'required',
        ]);

        try 
        {
            $debit = new Debit();
            $debit->concept = $request->input("concept");
            $debit->amount = $request->input("amount");
            $debit->id_alumno = $request->input("id_alumno");
            $debit->admin_id = Auth::guard("finance")->user()->id;
            $debit->save();
            session()->flash("messages","success|El alumno tiene un nuevo adeudo");
            return redirect()->back();
        } 
        catch (\Exception $th) 
        {
            session()->flash("messages","error|No pudimos guardar los datos");
            return redirect()->back();
        }
    }


    // accedemos a este método con ajax para cargar los datos de la orden 
    public function showPayementDetails(Request $request)
    {
        $debit = Debit::find($request->input("DebitId"));
        require_once("conekta/Conekta.php");
        \Conekta\Conekta::setApiKey("key_b6GSXASrcJATTGjgSNxWFg");
        \Conekta\Conekta::setApiVersion("2.0.0");
        $order = \Conekta\Order::find($debit->id_order);

        if ($request->input('is')=="card")
        {
            $data = array(
            "id"   => $order->id,
                "paymentMethod" => "Tarjeta",
                "amount"        =>  "$". $order->amount/100 . $order->currency,
                "order"         => $order->line_items[0]->quantity .
                                      "-". $order->line_items[0]->name .
                                      "- $". $order->line_items[0]->unit_price/100,
                  "type"=>"card"                   
            );
        }
        else
        {
            $data = array(
                "id"   => $order->id,
                "paymentMethod" => $order->charges[0]->payment_method->service_name,
                "reference"     => $order->charges[0]->payment_method->reference,
                "amount"        => "$".$order->amount/100 ." ". $order->currency,
                "order"         => $order->line_items[0]->quantity .
                                    "-". $order->line_items[0]->name .
                                    "- $". $order->line_items[0]->unit_price/100,
                "type"=> "nocard"                  
            );
        }
        return response()->json($data);
    }    
}
