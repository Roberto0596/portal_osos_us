<?php
namespace App\Http\Controllers\Alumn;
use Illuminate\Http\Request;
use Mpdf\Mpdf;
use App\Http\Controllers\Controller;
 
class PdfController extends Controller
{
    public function getIndex()
    {
        return view('pdf.index');
    }
    public function getGenerarCedula(Request $request , $tipo,$accion)
    {
       
        $matricula = $request->input('matriculaAlumno');
        
        $data = getDataByIdAlumn(getAlumnoId($matricula)[0]); 
        $charge = selectSicoes("Carga","AlumnoId",$data["AlumnoId"]);  
        $charge = $charge[count($charge)-1];
        $detGrupo = selectSicoes("DetGrupo","DetGrupoId",$charge["DetGrupoId"])[0];
        $group =  selectSicoes("EncGrupo","EncGrupoId",$detGrupo["EncGrupoId"])[0]['Nombre'];

        

        $accion = $accion;
        $data['tipo'] = $tipo;
       
        $alumno = getAlumno($matricula);
        $localidad_nacimiento = getEstadoMunicipio($matricula, 1);
        $localidad_residencia = getEstadoMunicipio($matricula, 2);

        $bachiller = selectSicoes("Escuela","EscuelaId",$alumno['EscuelaProcedenciaId'])[0];
        

        $datos_escolares['carrera'] = getCarrera($matricula);
        $datos_escolares['periodo'] = selectCurrentPeriod()['Clave'];
        $datos_escolares['semestre'] = getLastSemester(getAlumnoId($matricula)[0]);
        $datos_escolares['escuela_procedencia'] = $bachiller["Nombre"];
        $datos_escolares['grupo'] = $group;

        $data = selectSicoes("Alumno","Matricula",$matricula)[0];

        $data = selectSicoes("EncGrupo","planestudioid",$data["PlanEstudioId"]);

        
        

        $localidad_residencia = $alumno['Domicilio'].', '.$alumno['Colonia'].', '.$alumno['Localidad'].' - '.$localidad_residencia['municipio'].', '.$localidad_residencia['estado'].', '.$alumno['CodigoPostal'];
        
        
        

        if($accion=='html'){
            return view('pdf.generar',$alumno);
        }else{
            $html = view('pdf.generar',
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
       
        


        $accion = $accion;
        $data['tipo'] = $tipo;
        $matricula = $request->input('matriculaAlumno');
        $alumno = getAlumno($matricula);
        
        if($accion=='html'){
            return view('pdf.constancia',$alumno);
        }else{
            $html = view('pdf.constancia')->with('alumno', $alumno)->render();
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