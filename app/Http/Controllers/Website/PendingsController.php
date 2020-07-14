<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Website\Pending;
use Auth;

class PendingsController extends Controller
{
    public function loadData()
    {
        $alumns = selectSicoes("Alumno");   

        foreach($alumns as $alumn)
        {
            $pendig = new Pending();
            $pass = $this->generatePasssword();
            $pendig->enrollment = $alumn['Matricula'];
            $pendig->password   = $pass;
            $pendig->save();           
        }
        session()->flash('messages', 'success|Datos cargados correctamente');
        return redirect()->back();       
    }

    private function generatePasssword()
    {
        $letters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '1234567890';
        $password = '';
        for($i = 0; $i < 4; $i++){
            $randomIndexLetras = mt_rand(0,strlen($letters) - 1);
            $randomIndexNumbers = mt_rand(0,strlen($numbers) - 1);
            $password = $password.$letters[$randomIndexLetras].$numbers[$randomIndexNumbers];  
        }
        return $password;
    }

    public function generatePdf()
    {
        return view('Website\homepdf');
    }

    public function getPassword(Request $request)
    {
        $current_user = Auth::guard("alumn")->user(); 
        $pendings = Pending::all();
        $data = [];

        foreach ($pendings as $key => $value)
        {
            if ($value->enrollment == "16-05-0003")
            {
                $alumn = getAlumno($value->enrollment);
                $group = getLastThing("EncGrupo","PlanEstudioId",$alumn["PlanEstudioId"],"EncGrupoId");
                dd($group);
            }
        }

        $html = view('temp.index',
        ['alumn' => $current_user])->render();
        
        $namefile = 'Contraseñas'.time().'.pdf'; 
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
 
        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
        
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($html);
        $mpdf->Output($namefile,"I");      
    }
    
}

        //conseguir solo los nombres de las carreras en un solo arreglo
        // $carrer="";
        // foreach ($carrers as $key => $value) 
        // {
        //      $carrer=$carrer.",".$value["Nombre"]; 
        // }
        // $carrer = explode(",", $carrer);
