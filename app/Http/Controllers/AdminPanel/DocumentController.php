<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Alumns\Document;
use Illuminate\Support\Facades\DB;
use App\Models\Alumns\User;
use App\Models\Sicoes\Alumno;

class DocumentController extends Controller
{
   public function index()
   {
        return view('AdminPanel.document.index'); 
   }

    public function show(Request $request)
    {     
        $filter = $request->get('search') && isset($request->get('search')['value'])?$request->get('search')['value']:false;        
        $start = $request->get('start');
        $length = $request->get('length');

        $query = user::select();

        if($filter) {
            $query = $query->where(function($query) use ($filter){
                $query->orWhere('name', 'like', '%'. $filter .'%')
                    ->orWhere('lastname', 'like', '%'. $filter . '%')
                    ->orWhere('email', 'like', '%'. $filter . '%');
            });            
        }

        $filtered = $query->count();

        $query->skip($start)->take($length)->get();

        $data = $query->get();

        $res = [];      

        foreach($data as $key => $value)
        {
            try {
                $countSuccess = Document::where([['alumn_id',$value["id"]],["status", 2]])->get()->count();
                $alumnData = Alumno::find($value["id_alumno"]);           

                $files = Document::select('document.*','document_type.name')
                                    ->join('document_type','document.document_type_id','document_type.id')
                                    ->where('alumn_id',$value["id"])
                                    ->where("document.type",1)
                                    ->get();

                array_push( $res,[
                    "#"         => ($key+1),
                    "Matricula" => $alumnData->Matricula,
                    "Alumno"    => $alumnData->FullName,
                    "files"     => json_encode($files),
                    "countFiles"=> count($files),
                    "count"     => $countSuccess
                ]);  
            } catch(\Exception $e) {}            
        }
        
        return response()->json([
            "recordsTotal" => User::count(),
            "recordsFiltered" => $filtered,
            "data" => $res
        ]);
    }

    public function updateStatus(Request $request)
    {
        try {      
            $document = Document::find($request->document_id);
            $document->status = $request->value;
            $document->save();
            return response()->json("ok"); 
        } catch (\Throwable $th) {
            return response()->json("error");  
        }       
    }    
}
