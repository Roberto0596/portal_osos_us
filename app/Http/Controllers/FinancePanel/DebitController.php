<?php namespace App\Http\Controllers\FinancePanel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Alumns\Debit;
use App\Models\Alumns\Document;
use App\Models\Alumns\User;
use App\Models\Alumns\Ticket;
use App\Models\PeriodModel;
use App\Library\Inscription;
use App\Library\Ticket as TicketLibrary;
use App\Models\Sicoes\Alumno;
use Carbon\Carbon;
use Mpdf\Mpdf;
use Input;
use Auth;
use DB;

class DebitController extends Controller
{
    public function index()
	{
		return view('FinancePanel.debit.index');
    }

    //este metodo lo usamos con ajax para cargar los datos del adeudo para despues pasarlos al modal
	public function seeDebit(Request $request) 
	{       
        return response()->json(Debit::find($request->get("debit_id")));
    }

    public function searchAlumn(Request $request) {
        $filter = $request->get('filter');
        $res = [ "results" => []];

        $instance = Alumno::where(
        function ($query) use ($filter) {
            $query->where('Nombre', 'like', '%' .$filter. '%')
                ->orWhere('ApellidoPrimero', 'like', '%' .$filter. '%')
                ->orWhere('ApellidoSegundo', 'like', '%' .$filter. '%')
                ->orWhere('Matricula', 'like', '%' .$filter. '%');
        })->get();

        foreach ($instance as $key => $value) {
            array_push($res["results"], [
                "id" => $value->AlumnoId, 
                "text" => $value->Matricula ."|". $value->getFullName(),
            ]);
        }

        return response()->json($res);
    }
   
    // sirve para editar un adeudo 
    public function update(Request $request)
    {
        $debit = Debit::find($request->input("debitId"));   

        if ($debit) {
            $debit->amount = $request->input("amount");
            $debit->id_alumno = $request->input("id_alumno");
            $debit->description = $request->input("description");
            $debit->status = $request->get("status") == "on" ? 1 : 0; 
            $debit->save();   

            if ($debit->status == 1) {
                TicketLibrary::build($debit);
            }        
            
            if ($debit->has_file_id != null) {
                $document = Document::find($debit->has_file_id);
                $document->payment = $request->get("status") == "on" ? 1 : 0;
                $document->save();
            }

            session()->flash("messages","success|Guardado correcto");
            return redirect()->back(); 
        } else {
            session()->flash("messages","error|No se encontró el adeudo seleccionado");
            return redirect()->back();
        }      
    }

    public function validateDebit(Request $request) {

        $value = $request->get("verification") == "on" ? 1 : 0;

        $debit = Debit::find($request->input('debit_id'));

        if ($debit) {

            if ($value == 1) {
                $alumn = User::where("id_alumno","=",$debit->id_alumno)->first();

                $register = Inscription::makeRegister($alumn);

                if (count($register["errors"]) == 0) {
                    $message = $register["success"][0];
                    validateDebitsWithOrderId($debit->id_order, $value);
                } else {
                    $message = $register["errors"][0];
                }
            } else {
                validateDebitsWithOrderId($debit->id_order, $value);
            }

            $debit->save();
            session()->flash("messages", "info|".$message);
            return redirect()->back();

        } else {
            session()->flash("messages", "error|No fue posible encontrar el adeudo");
            return redirect()->back();
        }
        
    }

    //creamos un nuevo adeudos y se guarda el taba de debits
    public function save(Request $request) 
    {
        $request->validate([
            'description' => 'required',
            'debit_type_id'=>'required',
            'amount' => 'required',
            'id_alumno'=>'required',
        ]);

        try { 
            $alumn = Alumno::find($request->input("id_alumno"));           
            $debit = new Debit();
            $debit->debit_type_id = $request->input("debit_type_id");
            $debit->amount = $request->input("amount");
            $debit->description = $request->input("description");
            $debit->id_alumno = $request->input("id_alumno");
            $debit->admin_id = current_user("finance")->id;
            $debit->period_id = selectCurrentPeriod()->id;
            $debit->enrollment = $alumn->Matricula;
            $debit->alumn_name = $alumn->Nombre;
            $debit->alumn_last_name = $alumn->ApellidoPrimero;
            $debit->alumn_second_last_name = (isset($alumn->ApellidoSegundo) ? $alumn->ApellidoSegundo : '');
            $debit->career = $alumn->PlanEstudio->Carrera->Nombre;
            $debit->location = $alumn->Localidad;
            $debit->state = $alumn->Estado->Nombre;
            $debit->save();
            session()->flash("messages","success|El alumno tiene un nuevo adeudo");
            return redirect()->back();
        } catch (\Exception $th) {
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
                "type"=>"card"                   
            );
        }
        else if($request->input('is')=="spei")
        {
            $data = array(
                "id"   => $order->id,
                "paymentMethod" => "SPEI",
                "reference"     => $order->charges[0]->payment_method->receiving_account_number,
                "amount"        => "$". $order->amount/100 . $order->currency,
                "type"=> "spei"                  
            );           
        }
        else
        {
            $data = array(
                "id"   => $order->id,
                "paymentMethod" => $order->charges[0]->payment_method->service_name,
                "reference"     => $order->charges[0]->payment_method->reference,
                "amount"        => "$".$order->amount/100 ." ". $order->currency,
                "type"=> "nocard"                  
            );  
        }
        return response()->json($data);
    }

    public function delete($id)
    {
        try{
            $debit = Debit::find($id);

            //comprobar y borrar documento en caso de que tenga uno
            if ($debit->has_file_id != null) {
                Document::destroy($debit->has_file_id);
            }

            $debit->delete();
            session()->flash("messages","success|Se borro el adeudo con exito");
            return redirect()->back();
        } catch(\Exception $e) {
            session()->flash("messages","error|No se pudo eliminar el adeudo");
            return redirect()->back();
        }
    } 

    public function upload(Request $request)
    {
        try{
            //subir comprobante y guardar la informacion
            $debit = Debit::find($request->get('debit_id'));
            $file = $request->file('file');
            $path = 'img/comprobantes/';
            $name = uniqid().".".$file->getClientOriginalExtension();
            $file->move($path, $name); 
            $debit->id_order = $path.$name;
            $debit->payment_method = "transfer"; 
            $debit->save();
            session()->flash("messages","success|Comprobante Cargado con Éxito.");
            return redirect()->back();
        } catch(\Exception $e) {
            session()->flash("messages","error|Algo salió mal");
            return redirect()->back();
        }
    } 

    public function getTicket(Request $request) {

        $response = Ticket::where("debit_id", $request->get('debitId'))->first();

        if (!$response) {
           $debit = Debit::find($request->get('debitId'));
           $ticket = TicketLibrary::build($debit);
           $response = Ticket::where("debit_id", $debit->id)->first();
        }

        if ($response) {
            return response()->json(["status" => "success", "data" => $response]);
        } else {
            return response()->json(["status" => "error", "data" => null]);
        }
    }  

    public function ticketReport(Request $request) {
        $from = $request->get('initial_date');
        $to = Carbon::now()->addDay()->format('Y-m-d');
        
        if ($request->get('final_date')) {
            $to = $request->get('final_date');
        }

        $query = Ticket::select('ticket.route')
        ->join("debit as d", "ticket.debit_id", "=", "d.id");

        if ($from == $to) {
            $query = $query->where("ticket.created_at", "like", "%".$from."%");
        } else {
            $query = $query->whereBetween("ticket.created_at", [$from, $to]);
        }
        
        if ($request->has('debit_type_id')) {
            $query->where("d.debit_type_id", $request->get('debit_type_id'));
        }

        $query = $query->get();

        if ($query->count() == 0) {
            session()->flash("messages", "warning|No hay recibos en ese rango de fechas");
            return redirect()->back();
        }

        $pdf = new Mpdf();

        foreach ($query as $key => $value) {
            $page = $value->route;
            $newPage = $pdf->SetSourceFile($page);
            $finally = $pdf->ImportPage(1);
            $pdf->UseTemplate($finally);

            if ($key < ($query->count()-1)) {
                $pdf->addPage();
            }
        }

        $pdf->Output("report" . date("d-m-Y") . ".pdf","D");
    } 

    public function excelGenerate(Request $request) {
        $period_id = $request->get('period_id');
        $is_paid = $request->get('is_paid');
        $initial_date = $request->get('initial_date');
        $end_date = $request->get('end_date');

        $data = Debit::select();

        if ($period_id) {
            $data->where("period_id", $period_id);
        }

        if ($initial_date && $end_date) {

            if ($initial_date == $end_date) {
                $data->where("created_at", 'like', '%'.$initial_date.'%');
            } else {
                $data->whereBetween("created_at", [$initial_date, $end_date]);
            }

        } else if ($initial_date) {
            $data->where("created_at", '>=', '%' . $initial_date . '%');
        } else if ($end_date) {
            $data->where("created_at", '<=', '%' . $end_date . '%');
        }

        if ($is_paid != null) { 
            $data->where("status", intval($is_paid));
        }

        return response()->json($data->get());
    }

    public function datatable(Request $request)
    {      
        $filter = isset($request->get('search')['value']) && $request->get('search')?$request->get('search')['value']:false;
        $start = $request->get('start');
        $length = $request->get('length');
        $columns = $request->get('columns');
        $order = $request->get('order');

        $query = Debit::select("debit.*", 
            DB::raw("(CASE WHEN debit.status = 1 THEN 'Pagado' WHEN debit.status = 0 THEN 'Pendiente' END) AS convertStatus"),
            DB::raw("(CASE WHEN debit.payment_method = 'transfer' THEN 'Transferencia bancaria' WHEN debit.payment_method = 'oxxo_cash' THEN 'OXXO Paid' WHEN debit.payment_method = 'spei' THEN 'SPEI' WHEN debit.payment_method = 'card' THEN 'Pago con tarjeta'  END) AS convertMethod"))
            ->where("status", $request->get('status'))
            ->where("period_id", $request->get('period'));

        if ($request->get('concept') != "all") {
            $query->where("debit_type_id", $request->get('concept'));
        }

        if ($request->get('payment_method') != "all" && $request->get('payment_method')) {
            $query->where("payment_method", $request->get('payment_method'));
        }
        
        if ($filter) {
           $query = $query->where(function($query) use ($filter){
                $query->orWhere('description', 'like', '%'. $filter .'%')
                ->orWhere('enrollment', 'like', '%'. $filter .'%')
                ->orWhere('alumn_name', 'like', '%'. $filter .'%')
                ->orWhere('alumn_last_name', 'like', '%'. $filter .'%')
                ->orWhere('alumn_second_last_name', 'like', '%'. $filter .'%')
                ->orWhere('location', 'like', '%'. $filter .'%')
                ->orWhere('state', 'like', '%'. $filter .'%')
                ->orWhere('career', 'like', '%'. $filter .'%')
                ->orWhere('created_at', 'like', '%'. $filter .'%');
            });
        } 

        $filtered = $query->count();
        
        $query->skip($start)->take($length)->get();

        if(isset($order) && isset($order[0]) && $order[0]['column'] > -1) {
           $query->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'] );  
        }

        return response()->json([
            "recordsTotal" => Debit::count(),
            "recordsFiltered" => $filtered,
            "data" => $query->get()
        ]);
    }
}


