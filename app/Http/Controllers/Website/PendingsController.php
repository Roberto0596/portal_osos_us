<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Website\Pending;
use Auth;
use DB;

class PendingsController extends Controller
{
    public function loadData()
    {
        if (!DB::table('pendings')->count())
        {
            $alumns = selectSicoes("Alumno");   

            foreach($alumns as $value)
            {
                $EncGrupoId = getInscriptionData($value["AlumnoId"]);
                if ($EncGrupoId != false) 
                {
                    $pendig = new Pending();
                    $pass = $this->generatePasssword();
                    $pendig->enrollment = $value['Matricula'];
                    $pendig->PlanEstudioId = $value['PlanEstudioId'];
                    $pendig->EncGrupoId = $EncGrupoId["EncGrupoId"];
                    $pendig->password   = $pass;
                    $pendig->save(); 
                } 
                else
                {

                }         
            }
            session()->flash('messages', 'success|Datos cargados correctamente');
            return redirect()->back();
        }
        else
        {
            session()->flash('messages', 'info|Ya estan registrado');
            return redirect()->back();
        }       
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

    public function print()
    {
        if (session()->has("data"))
        {     
            $data = session()->get("data");
            dd($data);  
            $html = view('temp.index',
            ['alumn' => $current_user,'data'=>$data[0]])->render();
            
            $namefile = 'Contraseñas'.time().'.pdf'; 
            $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
            $fontDirs = $defaultConfig['fontDir'];
     
            $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
            $fontData = $defaultFontConfig['fontdata'];
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);

            $index = session()->get("index")+1;
            session()->forget("index");
            session(["index"=>$index]);
            
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML($html);
            $mpdf->Output($namefile,"I"); 
        }
        else
        {
            session()->flash("messages","info|no se an creado los grupos");
            return redirect()->back();
        }
    }

    public function generateGroups(Request $request)
    {
        if (!session()->has("data"))
        {
            $current_user = Auth::guard("alumn")->user(); 
            $pendings = Pending::all();

            $planEstudio = getGroups("pendings","PlanEstudioId");
            $groups = getGroups("pendings","EncGrupoId");

            $data = [];

            foreach ($planEstudio as $key => $value)
            {
                foreach ($groups as $key2 => $value2) 
                {
                    $aux = agruparPorSalon($value["PlanEstudioId"],$value2["EncGrupoId"]);
                    if(count($aux)!=0)
                    {
                        array_push($data, $aux);
                    }
                }
            }

            foreach ($data as $key => $value)
            {
                foreach ($data[$key] as $key2 => $value2) 
                {
                    $aux = Pending::where("enrollment","=",$value2["Matricula"])->get();
                    if(!$aux->isEmpty())
                    {
                        array_push($data[$key][$key2], $aux[0]->password);
                    }
                    else
                    {
                        unset($data[$key][$key2]);
                    }
                }
            }
            $index = 0;
            $data = array('data' => $data, 'index' => $index);
            session($data);
            session()->flash("messages","success|Se crearon los grupos");
            return redirect()->back();  
        }
        else
        {
            session(["data",$data,"index" => $index]);
            session()->flash("messages","info|ya se habian creado los grupos");
            return redirect()->back(); 
        }
    }
}        
