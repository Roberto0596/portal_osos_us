<?php
namespace App\Http\Controllers\Alumn;
use Illuminate\Http\Request;
use Mpdf\Mpdf;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\Alumns\Debit;
 
class PdfController extends Controller
{
    public function index()
    {
        return view('Alumn.pdf.index');
    }
    public function getGenerarCedula(Request $request,$tipo,$accion)
    {
        $current_user = Auth::guard("alumn")->user();
        $alumno = getDataByIdAlumn($current_user->id_alumno);            
        $charge = selectSicoes("Carga","AlumnoId",$alumno["AlumnoId"]);  
        $charge = $charge[count($charge)-1];
        $detGrupo = selectSicoes("DetGrupo","DetGrupoId",$charge["DetGrupoId"])[0];
        $group =  selectSicoes("EncGrupo","EncGrupoId",$detGrupo["EncGrupoId"])[0]['Nombre'];
        $accion = $accion;
        $data['tipo'] = $tipo;      
        $localidad_nacimiento = getEstadoMunicipio($alumno['Matricula'], 1);
        $localidad_residencia = getEstadoMunicipio($alumno['Matricula'], 2);
        $bachiller = selectSicoes("Escuela","EscuelaId",$alumno['EscuelaProcedenciaId'])[0];
        $datos_escolares['carrera'] = getCarrera($alumno['Matricula']);
        $datos_escolares['periodo'] = selectCurrentPeriod()['Clave'];
        $datos_escolares['semestre'] = getLastSemester(getAlumnoId($alumno['Matricula'])[0]);
        $datos_escolares['escuela_procedencia'] = $bachiller["Nombre"];
        $datos_escolares['grupo'] = $group;
        $localidad_residencia = $alumno['Domicilio'].', '.$alumno['Colonia'].', '.$alumno['Localidad'].' - '.$localidad_residencia['municipio'].', '.$localidad_residencia['estado'].', '.$alumno['CodigoPostal'];    

        if($accion=='html')
        {
            return view('Alumn.pdf.generar',$alumno);
        }
        else
        {
            $html = view('Alumn.pdf.generar',
            ['alumno' => $alumno,
            'lugar_nacimiento' => $localidad_nacimiento,
            'direccion' => $localidad_residencia,
            'datos_escolares' => $datos_escolares])->render();
        }
        $namefile = 'CEDULA'.time().'.pdf'; 
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
 
        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
       
        $mpdf->SetDisplayMode('fullpage');
       

        $mpdf->WriteHTML($html);
        


        if($accion=='ver'){
            $mpdf->Output($namefile,"I");
        }elseif($accion=='descargar'){
            $mpdf->Output($namefile,"D");
        }
    
       
    }
    public function getGenerarConstancia(Request $request , $tipo,$accion)
    {
       

    

       
        $current_user = Auth::guard("alumn")->user();
        $alumno = getDataByIdAlumn($current_user->id_alumno);

        $accion = $accion;
        $data['tipo'] = $tipo;
        
        
        
        if($accion=='html'){
            return view('Alumn.pdf.constancia',$alumno);
        }else{
            $html = view('Alumn.pdf.constancia')->with('alumno', $alumno)->render();
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
            if($pago == 'transferencia'){
                $html = view('Alumn.pdf.pago_transferencia',['alumno' => $alumno,
                'deuda_total' => $total])->render();
            }else{
                $html = view('Alumn.pdf.pago_banco',
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