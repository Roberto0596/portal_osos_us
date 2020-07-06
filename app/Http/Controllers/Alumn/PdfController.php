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
    public function getGenerar(Request $request , $tipo,$accion)
    {
       
       
        $accion = $accion;
        $data['tipo'] = $tipo;
       
        
        if($accion=='html'){
            return view('pdf.generar',$data);
        }else{
            $html = view('pdf.generar',$data)->render();
        }
        $namefile = 'CEDULA'.time().'.pdf';
 
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
 
        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
       
        $mpdf->WriteHTML($html);
        
        if($accion=='ver'){
            $mpdf->Output($namefile,"I");
        }elseif($accion=='descargar'){
            $mpdf->Output($namefile,"D");
        }
    
       
    }
    
   
}