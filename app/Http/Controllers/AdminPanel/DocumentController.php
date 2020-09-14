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
                if($value->status === 1){
                    $countSuccess++;
                }
            }

            if($countSuccess === 5){

                $statusButton = "<button type='submit' class='btn btn-success'>
                <i class='fa fa-check-circle' title='Completado'></i></button> &nbsp&nbsp&nbsp ".$countSuccess." / 5 Validados";

            }else{

                $statusButton = "<button type='submit' class='btn btn-danger custom'>
                <i class='fa fa-exclamation-circle' title='Sin Validar'></i></button> &nbsp&nbsp&nbsp ".$countSuccess." / 5 Validados";
            }


            $files = DB::table('document')->where('alumn_id',$value->alumn_id )->get();
            ///dd(count($files));



            $showFiles =  "<button class='btn btn-warning custom ShowFiles' files='".$files."'
              data-toggle='modal'  data-target='#modalDocuments' title='Ver documentos'><i class='fa fa-eye'></i> 
             &nbsp&nbsp Ver </button></div>  &nbsp&nbsp&nbsp ".count($files)." / 5 Subidos";
          
           

            array_push( $res["data"],[
                (count($alums_list)-($key+1)+1),
                $querySicoes[0]["Matricula"],
                $alumn->name." ".$alumn->lastname,
                $statusButton,
                $showFiles
               
            ]);
        }
        
        return response()->json($res);  

       
       

        

       

    }
    
}
