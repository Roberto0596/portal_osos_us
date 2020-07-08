<?php

namespace App\Http\Controllers\LibraryPanel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Alumns\Debit;
use Input;
use Auth;

class DebitController extends Controller
{
	public function index()
	{
		return view('LibraryPanel.debit.index');
    }
    
    public function showDebit(Request $request)
    {
        $current_user = Auth::guard("library")->user();
        $res = [ "data" => []];
        $debits = Debit::where("admin_id",$current_user->id)->get();

        foreach($debits as $key => $value)
        {
            $buttons="<div class='btn-group'>";
            if($value->status==1)
            {
                $buttons.="<button class='btn btn-primary printDetails' orderId='".$value->id_order."' title='Ver datos de pago'>
                <i class='fa fa-file'></i></button>
                </div>";
            }
            else
            {
                $buttons.="
                <button class='btn btn-warning pay' data-toggle='modal' data-target='#modalPay' DebitId='".$value->id."' title='Cobrar'>
                <i class='fa fa-credit-card' style='color:white'></i></button>
                </div>"; 
            }
            $alumn = selectSicoes("Alumno","AlumnoId",$value->id_alumno)[0];
            array_push($res["data"],[
                (count($debits)-($key+1)+1),
                $value->concept,
                "$".number_format($value->amount,2),
                $current_user->name,
                $alumn["Nombre"],
                ($value->status==1)?"Pagada":"Pendiente",
                $value->created_at,
                $buttons
            ]);
        }

        return response()->json($res);
    }

	public function seeDebit(Request $request) 
	{
        $debit = Debit::find($request->input("DebitId"));
        $alumn = selectSicoes("Alumno","AlumnoId",$debit->id_alumno)[0];
        $data = array("concept"=>$debit->concept,
                        "alumnName"=>$alumn["Nombre"].$alumn["ApellidoPrimero"],
                        "amount"=>$debit->amount,
                        "DebitId" => $debit->id);
        return response()->json($data);
    }

    public function update(Request $request)
    {
        try 
        {
            $debit = Debit::find($request->input("DebitId"));
            $debit->status = 1;
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

    public function delete($id)
    {
    }

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
            $debit->admin_id = Auth::guard("computercenter")->user()->id;
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

}