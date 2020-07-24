<?php
namespace App\Http\Controllers\Alumn;
use Illuminate\Http\Request;
use Mpdf\Mpdf;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\Alumns\Debit;
use App\Models\Alumns\Document;

class PdfController extends Controller
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

    public function getGenerarCedula(Request $request, $id)
    {
        $current_user = Auth::guard("alumn")->user();

        $document = Document::where([["alumn_id","=",$current_user->id],["id","=",$id]])->first();
        if ($document==null) {
            return redirect()->back();
        }

        $alumno = getDataByIdAlumn($current_user->id_alumno);

        $inscripcion = getLastThing("Inscripcion","AlumnoId",$current_user->id_alumno,"InscripcionId");            
        $group =  selectSicoes("EncGrupo","EncGrupoId",$inscripcion["EncGrupoId"])[0];

        $localidad_nacimiento = getEstadoMunicipio($alumno['Matricula'], 1);
        $localidad_residencia = getEstadoMunicipio($alumno['Matricula'], 2);

        $bachiller = selectSicoes("Escuela","EscuelaId",$alumno['EscuelaProcedenciaId'])[0];

        $datos_escolares = array('carrera' => getCarrera($alumno['Matricula']),
                                 'periodo' => selectCurrentPeriod()['Clave'],
                                 'semestre' => $inscripcion["Semestre"],
                                 'escuela_procedencia'=>$bachiller["Nombre"],
                                 'grupo' => $group["Nombre"]);

        $localidad_residencia = $alumno['Domicilio'].', '.$alumno['Colonia'].', '.$alumno['Localidad'].' - '.$localidad_residencia['municipio'].', '.$localidad_residencia['estado'].', '.$alumno['CodigoPostal'];    

        $html = view('Alumn.pdf.generar',['alumno' => $alumno,'lugar_nacimiento' => $localidad_nacimiento,
        'direccion' => $localidad_residencia,'datos_escolares' => $datos_escolares])->render();
        
        $namefile = 'CEDULA'.time().'.pdf'; 
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
 
        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);

        // $document->status = 1;
        $document->save();
        
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($html);
        $mpdf->Output($namefile,"I"); 
        return redirect(Request::url());     
    }

    public function getGenerarConstancia(Request $request, $id)
    {      
        $current_user = Auth::guard("alumn")->user();

        $document = Document::where([["alumn_id","=",$current_user->id],["id","=",$id]])->first();
        if ($document==null) {
            return redirect()->back();
        }

        $alumno = getDataByIdAlumn($current_user->id_alumno);
 
        $html = view('Alumn.pdf.constancia')->with('alumno', $alumno)->render();
        $namefile = 'CONSTANCIA'.time().'.pdf';
 
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
 
        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);

        // $document->status = 1;
        $document->save();
       
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($html);
        $mpdf->Output($namefile,"I");   
        return redirect(Request::url());   
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
            return view('Alumn.pdf.pago_transferencia',$alumno);
        }else{
           if($pago == 'ficha'){

                $html = view('Alumn.pdf.ficha',
                ['alumno' => $alumno,
                'deuda_total' => $total])->render();
            }
            
            
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