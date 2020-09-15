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

    public function show()
    {

     
        $alums_list = DB::table('document')->distinct()->get(['alumn_id']);

        $res = [ "data" => []];      

        foreach($alums_list as $key => $value)
        {

            $alumn = User::find($value->alumn_id);
            $documents = DB::table('document')->where('alumn_id' , '=' , $alumn->id)->get();
            $querySicoes = selectSicoes("Alumno","AlumnoId",$alumn->id_alumno);

            $countSuccess = 0;
            foreach($documents as $value)
            {
                if($value->status === 2){
                    $countSuccess++;
                }
            }
          

            $files = DB::table('document')->where('alumn_id',$value->alumn_id )->get();
            $files_to_send = json_encode($files);

           

            array_push( $res["data"],[
                "#"         => (count($alums_list)-($key+1)+1),
                "Matricula" => $querySicoes[0]["Matricula"],
                "Alumno"    => $alumn->name." ".$alumn->lastname,
                "files"     => $files_to_send,
                "countFiles"=> count($files),
                "count"     => $countSuccess
            ]);
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
