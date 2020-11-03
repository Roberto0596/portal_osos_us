<?php

namespace App\Http\Controllers\AdminPanel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AdminUsers\DocumentType;

class DocumentTypeController extends Controller
{
    public function index()
    {
        return view('AdminPanel.DocumentType.index'); 
    }

     public function show()
    {
        $documentsTypes = DocumentType::orderByDesc('id')->get();
       

        $res = [ "data" => []];       

        foreach($documentsTypes as $key => $value)
        {
        
            if( $value->can_delete === 1){

                $buttons="<div class='btn-group'>
                    <button class='btn btn-warning custom btnEdit'  doc_type_id = '".$value->id."' title='Editar'><i class='fa fa-pen' style='color:white'></i></button></div>
                    <button class='btn btn-danger btnDelete' doc_type_id = '".$value->id."' title='Eliminar'><i class='fa fa-times'></i></button>
                </div>";

            }else{


                $buttons="<div class='btn-group'>
                    <button class='btn btn-warning custom btnEdit'  doc_type_id = '".$value->id."' title='Editar'><i class='fa fa-pen' style='color:white'></i></button></div>
                </div>";

            }

            array_push($res["data"],[
                (count($documentsTypes)-($key+1)+1),
                $value->name,
                $value->type === 1 ? "Si" : "No",
                $value->cost !== null ? "$".$value->cost : "Sin Costo",
                $buttons
            ]);
        }

        return response()->json($res);  
    }

    public function create(Request $request) 
    {
        $validatedData = [];
        if($request->input('type')  == 1){

            $validatedData = $request->validate([
                'name' => ['required'],
                'type' => ['required'],
                'cost' => ['required'],
                'can_delete'=>['required'],
            ]);

        }else{
            $validatedData = $request->validate([
                'name' => ['required'],
                'type' => ['required'],
                'can_delete'=>['required'],
            ]);

            $validatedData['cost'] = null;
        }


      
        
        
        try 
        {
            DocumentType::create($validatedData);

            session()->flash("messages","success|Tpo de Documento Creado");
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
        try
        {
           $delete = DocumentType::destroy($id);
           session()->flash("messages","success|Tipo de Adeudo eliminado con éxito");
           return redirect()->back();
        }
        catch(\Exception $e)
        {
            session()->flash("messages","error|Tuvimos problemas eliminando el Tipo de Adeudo");
           return redirect()->back();
        }        
    }

   

    

  
   
	public function see(Request $request) 
	{     
        
        $docType = DocumentType::find($request->input("id"));
        $data = array(
            "name"    => $docType->name,
            "type"    => $docType->type,
            "cost"    => $docType->cost,
            "can_delete" => $docType->can_delete
        );
        return response()->json($data);
    }

     


    public function update(Request $request)
    {
    
        try {
            
            $docType = DocumentType::find($request->input("id"));
            $docType->name = $request->input("name");
            $docType->type = $request->input("type");
            $docType->cost = $request->input('cost');
            $docType->can_delete = $request->input("can_delete");
            $docType->save();
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
