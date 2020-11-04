<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminUsers\DebitType;

class DebitTypeController extends Controller
{
    public function index()
    {
        return view('AdminPanel.DebitType.index'); 
    }

    public function show()
    {
        $debitstypes = DebitType::orderByDesc('id')->get();
       

        $res = [ "data" => []];       

        foreach($debitstypes as $key => $value)
        {
        
            if( $value->can_delete === 1){

                $buttons="<div class='btn-group'>
                    <button class='btn btn-warning custom btnEdit'  debit_type_id = '".$value->id."' title='Editar'><i class='fa fa-pen' style='color:white'></i></button></div>
                    <button class='btn btn-danger btnDelete' debit_type_id = '".$value->id."' title='Eliminar'><i class='fa fa-times'></i></button>
                </div>";

            }else{


                $buttons="<div class='btn-group'>
                    <button class='btn btn-warning custom btnEdit'  debit_type_id = '".$value->id."' title='Editar'><i class='fa fa-pen' style='color:white'></i></button></div>
                </div>";

            }

            array_push($res["data"],[
                (count($debitstypes)-($key+1)+1),
                $value->concept,
                $buttons
            ]);
        }

        return response()->json($res);  
    }

    public function delete($id)
    {
        try
        {
           $delete = DebitType::destroy($id);
           session()->flash("messages","success|Tipo de Adeudo eliminado con éxito");
           return redirect()->back();
        }
        catch(\Exception $e)
        {
            session()->flash("messages","error|Tuvimos problemas eliminando el Tipo de Adeudo");
           return redirect()->back();
        }        
    }

    public function create(Request $request) 
    {

        $validatedData = $request->validate([
            'concept' => ['required'],
            'can_delete'=>['required'],
        ]);
        
        try 
        {
            DebitType::create($validatedData);

            session()->flash("messages","success|Tpo de Adeudo Creado");
            return redirect()->back();
        } 
        catch (\Exception $th) 
        {
            session()->flash("messages","error|No pudimos guardar los datos");
            return redirect()->back();
        }
    }

   
	public function see(Request $request) 
	{     
        
        $debitType = DebitType::find($request->input("id"));
        $data = array(
            "concept"    => $debitType->concept,
            "can_delete" => $debitType->can_delete
        );
        return response()->json($data);
    }


    public function update(Request $request)
    {
     
        try {
            
            $debitType = DebitType::find($request->input("id"));
            $debitType->concept = $request->input("concept");
            $debitType->can_delete = $request->input("can_delete");
            $debitType->save();
            session()->flash("messages","success|Guardado correcto");
            return redirect()->back(); 
        } 
        catch (\Exception $th) 
        {
            session()->flash("messages","error|No pudimos guardar los datos");
            return redirect()->back();
        }
    }

}
