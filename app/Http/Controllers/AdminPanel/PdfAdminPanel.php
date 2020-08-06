<?php
namespace App\Http\Controllers\Alumn;
use Illuminate\Http\Request;
use Mpdf\Mpdf;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\Alumns\Debit;
use App\Models\Alumns\Document;

class PdfAdminController extends Controller
{
    public function index()
    {
        return view('Alumn.pdf.index');
    }

    public function showDocuments(Request $request)
    {
        $current_user = Auth::guard("alumn")->user();
        $res = [ "data" => []];

        $query = [["alumn_id","=",$current_user->id],["status","=","0"]];
        $documents = Document::where($query)->get();

        foreach($documents as $key => $value)
        {
            $buttons="<div class='btn-group'><a class='btn btn-primary reload' target='_blank' href='".route($value->route,$value)."' title='Imprimir'>
            <i class='fa fa-file'></i></a>
            </div>";

            array_push($res["data"],[
                (count($documents)-($key+1)+1),
                $value->name,
                $buttons
            ]);
        }

        return response()->json($res);
    }

    public function getGenerarFicha(Request $request , $tipo,$accion, $pago)
    {
 

        $query = [["id_alumno","=",Auth::guard("alumn")->user()->id_alumno],["status","=","0"]];
        $total = Debit::where($query)->get()->sum("amount");

        
        
        $current_user = Auth::guard("alumn")->user();
        $alumno = getDataByIdAlumn($current_user->id_alumno);

        $accion = $accion;
        $data['tipo'] = $tipo;
        
        
        
        if($accion=='html'){
            return view('AdminPanel.pdf.tabla_alumnos',$alumno);
        }else{
            $html = view('AdminPanel.pdf.tabla_alumnos',
            ['alumno' => $current_user,
            'deuda_total' => $total])->render();
            
            
        }
        $namefile = 'CONSTANCIA'.time().'.pdf';
 
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
 
        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
        $mpdf = new Mpdf([
            'fontDir' => array_merge($fontDirs, [
                public_path() . '/fonts',
            ]),
            'fontdata' => $fontData + [
                'arial' => [
                    'R' => 'arial.ttf',
                    'B' => 'arialbd.ttf',
                ],
            ],
            'default_font' => 'arial',
            "format" => [210,297],
        ]);
       
        $mpdf->SetDisplayMode('fullpage');

        

        $mpdf->WriteHTML($html);
        


        if($accion=='ver'){
            $mpdf->Output($namefile,"I");
        }elseif($accion=='descargar'){
            $mpdf->Output($namefile,"D");
        }
    
       
    }
}