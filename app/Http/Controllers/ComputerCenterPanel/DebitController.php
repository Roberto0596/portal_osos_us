<?php

namespace App\Http\Controllers\ComputerCenterPanel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Alumns\Debit;
use App\Models\PeriodModel;
use Input;
use Auth;

class DebitController extends Controller
{
	public function index()
	{
        $periods = PeriodModel::select()->orderBy("id")->get();
		return view('ComputerCenterPanel.debit.index')->with(["periods" => $periods]);
    }
    
    public function showDebit(Request $request)
    {   
        $res = [];

        if (session()->has('mode')) 
        {
            session()->forget('mode');
        }
        session([
            "mode"=>[
                "mode" => $request->input('mode'), 
                "period" => $request->input('period'),
                "concept" => $request->input('concept')
            ]
        ]);

        $filter = isset($request->get('search')['value']) && $request->get('search')  ?$request->get('search')['value']:false;
        $start = $request->get('start');
        $length = $request->get('length');
        $filtered = 0;
        $query = Debit::where("admin_id", Auth::guard('computercenter')->user()->id);
        $query->where([["status","=",$request->input('mode')],["period_id","=",$request->input('period')]]);
        $query->where([["debit_type_id","<>", 1],["debit_type_id","<>",5]]);

        if ($request->get('concept') != "all") {
            $query->where("debit_type_id", $request->get('concept'));
        }

        $filtered = $query->count();
        
        if ($filter) {
           $query = $query->where(function($query) use ($filter){
                $query->orWhere('description', 'like', '%'. $filter .'%');
            });
           $filtered = $query->count();
        } 
        
        $query->skip($start)->take($length)->get();
        $debits = $query->get();

        foreach($debits as $key => $value)
        {
            if ($value->id_alumno != null) {
                $alumn = getDataAlumnDebit($value->id_alumno);
                array_push($res,[
                    "#" => ($key+1),
                    "Alumno" => ucwords(strtolower($alumn["Nombre"]." ".$alumn["ApellidoPrimero"]." ".$alumn["ApellidoSegundo"])),
                    "Email" =>strtolower($alumn["Email"]),
                    "Descripción" => $value->description,
                    "Importe" => "$".number_format($value->amount,2),
                    "Matricula" =>$alumn["Matricula"],
                    "Estado" =>($value->status==1)?"Pagada":"Pendiente",
                    "Fecha" => substr($value->created_at,0,11),
                    "Carrera" =>$alumn['nombreCarrera'],
                    "Localidad" =>$alumn["Localidad"].", ".$alumn['nombreEstado'],
                    "method" => $value->payment_method,
                    "debitId" => $value->id,
                    "id_order" => $value->id_order,
                    "debit_type_id" => $value->debit_type_id
                ]);
            }
        }
        return response()->json([
            "recordsTotal" => Debit::count(),
            "recordsFiltered" => $filtered,
            "data" => $res
        ]);
    }

	//este metodo lo usamos con ajax para cargar los datos del adeudo para despues pasarlos al modal
    public function seeDebit(Request $request) 
    {       
        $debit = Debit::find($request->input("DebitId"));
        $alumn = selectSicoes("Alumno","AlumnoId",$debit->id_alumno)[0];
        $data = array(
            "concept"   => $debit->debitType->concept,
            "alumnName" =>$alumn["Nombre"]." ".$alumn["ApellidoPrimero"],
            'description'=>$debit->description,
            "amount"    =>$debit->amount,
            "debitId"   => $debit->id,
            "alumnId" => $debit->id_alumno,
            "status"    => $debit->status,
            "id_order" => $debit->id_order, 
            "method" => $debit->payment_method,
            "enrollment" => $alumn["Matricula"],
        );

        return response()->json($data);
    }

    public function update(Request $request)
    {
        try 
        {
            $debit = Debit::find($request->input("debitId"));
            $debit->amount = $request->get('amount');
            $debit->description = $request->get('description');
            $debit->save();
            session()->flash("messages","success|Se guardo correctamente");
            return redirect()->back();
        } 
        catch (\Exception $th) 
        {
            session()->flash("messages","error|No pudimos guardar los datos");
            return redirect()->back();
        }
    }

    public function save(Request $request) 
    {
        $request->validate([
            'debit_type_id' => 'required',
            'amount' => 'required',
            'id_alumno'=>'required',
        ]);

        try 
        {
            $debit = new Debit();
            $debit->debit_type_id = $request->input("debit_type_id");
            $debit->amount = $request->input("amount");
            $debit->description = $request->input("description");
            $debit->id_alumno = $request->input("id_alumno");
            $debit->admin_id = Auth::guard("computercenter")->user()->id;
            $debit->period_id = selectCurrentPeriod()->id;
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

    public function delete($id)
    {
        try{
            Debit::destroy($id);
            session()->flash("messages","success|Se borro el adeudo con exito");
            return redirect()->back();
        } catch(\Exception $e) {
            session()->flash("messages","error|No se pudo eliminar el adeudo");
            return redirect()->back();
        }
    } 

}