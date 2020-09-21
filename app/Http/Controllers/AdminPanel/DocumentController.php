<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Alumns\Document;
use Illuminate\Support\Facades\DB;
use App\Models\Alumns\User;

class DocumentController extends Controller
{
   public function index()
   {
    return view('AdminPanel.document.index'); 
   }

    public function show(Request $request)
    {     
        $start = $request->get('start');
        $length = $request->get('length');
        $alums_list = User::all();

        if($alums_list) {
            $alums_list->skip($start)->take($length);
        } else {
            $alums_list = User::skip($start)->take($length);
        }

        $res = [ "data" => []];      

        foreach($alums_list as $key => $value)
        {
            try {
                $countSuccess = Document::where([['alumn_id',$value["id"]],["status", 2]])->get()->count();
                $querySicoes = selectSicoes("Alumno","AlumnoId",$value["id_alumno"]);           

                $files = Document::select('document.*','document_type.name')->join('document_type','document.document_type_id','document_type.id')->where('alumn_id',$value["id"])->get();

                array_push( $res["data"],[
                    "#"         => (count($alums_list)-($key+1)+1),
                    "Matricula" => $querySicoes[0]["Matricula"],
                    "Alumno"    => $value["name"]." ".$value["lastname"],
                    "files"     => json_encode($files),
                    "countFiles"=> count($files),
                    "count"     => $countSuccess
                ]);  
            } catch(\Exception $e) {
            }            
        }
        
        return response()->json($res);  

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