<?php

namespace App\Http\Controllers\Alumn;

use Auth;
use App\Models\Alumns\Debit;
use Illuminate\Http\Request;
use App\Models\Alumns\Ticket;
use App\Models\Alumns\Document;
use App\Models\AdminUsers\Problem;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $user = current_user();
        	$status = $user->inscripcion < 3? false:true;

        //documentos
        $documents = Document::where("alumn_id","=",$user->id)->get();

        //adeudos
        $query = [["id_alumno","=",$user->id_alumno],["status","=","0"],["debit_type_id","<>", 1]];
        $debit = Debit::where($query)->get();
        $total = $debit->count("amount");


        //tickets
        $tickets = Ticket::where("alumn_id","=",$user->id)->get();

        return view('Alumn.home.index')->with(["status"=>$status,'documents'=>$documents,'debits'=>$total,'tickets'=>$tickets]);
    }

    public function saveProblem(Request $request)
    {
        $request->validate([
            'text' => 'required',
        ]);
        try
        {
            $problem = new Problem();
            $problem->text = $request->input("text");
            $problem->alumn_id = Auth::guard("alumn")->user()->id;
            $problem->save();
            session()->flash("messages","success|Se envio el problema correctamente");
            return redirect()->back();
        }
        catch(\Exception $e)
        {
            session()->flash("messages","error|Tenemos problemas en enviar su problema");
            return redirect()->back();
        }
    }
}