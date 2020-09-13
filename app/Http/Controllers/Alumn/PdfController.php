<?php
namespace App\Http\Controllers\Alumn;
use Illuminate\Http\Request;
use Mpdf\Mpdf;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\Alumns\Debit;
use App\Models\Alumns\Document;
use App\Models\Alumns\DocumentType;
use App\Models\PeriodModel;

class PdfController extends Controller
{
    public function index()
    {
        return view('Alumn.pdf.index');
    }

    public function showDocuments(Request $request)
    {
        $current_user = current_user();

        $res = [ "data" => []];

        $documents = Document::where("alumn_id","=",$current_user->id)->get();

        foreach($documents as $key => $value)
        {
            try {
                $buttons="<div class='btn-group'><a class='btn btn-primary reload' target='_blank' href='".route($value->route,$value)."' title='Imprimir'>
                <i class='fa fa-file'></i></a>
                </div>";
            } catch(\Exception $e) {
                $buttons="<div class='btn-group'><a class='btn btn-primary printDocument' target='_blank' href='".$value->route."' title='Imprimir'>
                <i class='fa fa-file'></i></a>
                </div>";
            }

            $document_type = DocumentType::find($value->document_type_id);
            $period = PeriodModel::find($value->PeriodoId);

            array_push($res["data"],[
                (count($documents)-($key+1)+1),
                $document_type->name,
                $value->description,
                $period->clave,
                $value->created_at,
                $buttons
            ]);
        }

        return response()->json($res);
    }

    public function getGenerarCedula(Request $request, $id)
    {
        $current_user = Auth::guard("alumn")->user();

        $document = Document::where([["alumn_id","=",$current_user->id],["id","=",$id]])->first();
        $currentPeriod = selectCurrentPeriod();
         
        if ($document==null) {
            return redirect()->back();
        }

        try
        {
            $alumno = selectSicoes("Alumno","AlumnoId",$current_user->id_alumno)[0];
            $inscripcion = getInscription($current_user->id_alumno);
            $group = selectSicoes("EncGrupo","EncGrupoId",$inscripcion["EncGrupoId"])[0];
        }
        catch(\Exception $e)
        {
            session()->flash("messages","error|Ocurrio un problema, no se encontro tu registro de sicoes");
            return redirect()->back();
        }   

        $localidad_nacimiento = getEstadoMunicipio($alumno['Matricula'], 1);
        $localidad_residencia = getEstadoMunicipio($alumno['Matricula'], 2);

        try {
            $bachiller = selectSicoes("Escuela","EscuelaId",$alumno['EscuelaProcedenciaId'])[0];        
        } catch (\Exception $e) {
            $bachiller = ['Nombre' => 'Sin dato'];
        }       

        $datos_escolares = array('carrera' => getCarrera($alumno['Matricula']),
                                 'periodo' => $currentPeriod->clave,
                                 'semestre' => $inscripcion["Semestre"],
                                 'escuela_procedencia'=>$bachiller["Nombre"],
                                 'grupo' => $group["Nombre"]);


        $localidad_residencia = $alumno['Domicilio'].', '.$alumno['Colonia'].', '.$alumno['Localidad'].' - '.$localidad_residencia['municipio'].', '.$localidad_residencia['estado'].', '.$alumno['CodigoPostal']; 

        $html = view('Alumn.pdf.generar', ['alumno' => $alumno,'lugar_nacimiento' => $localidad_nacimiento,
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

        try
        {
            $alumno = selectSicoes("Alumnos","AlumnoId",$current_user->id_alumno)[0];
        }
        catch(\Exception $e)
        {
            session()->flash("messages","error|Ocurrio un problema, no se encontro tu registro de sicoes");
            return redirect()->back();
        }
 
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

    public function getGenerarFicha(Request $request, $tipo, $accion, $pago)
    {
        $query = [["id_alumno","=",Auth::guard("alumn")->user()->id_alumno],["status","=","0"]];
        $total = Debit::where($query)->get()->sum("amount");        
        $current_user = Auth::guard("alumn")->user();
        try
        {
            $alumno = selectSicoes("Alumnos","AlumnoId",$current_user->id_alumno)[0];
        }
        catch(\Exception $e)
        {
            session()->flash("messages","error|Ocurrio un problema, no se encontro tu registro de sicoes");
            return redirect()->back();
        }
        $data['tipo'] = $tipo;                   

        $html = view('Alumn.pdf.ficha',['alumno' => $alumno,'deuda_total' => $total])->render();
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
        if($accion=='ver')
        {
            $mpdf->Output($namefile,"I");
        }
        else if($accion=='descargar')
        {
            $mpdf->Output($namefile,"D");
        }      
    } 

    public function tabCache(Request $request)
    {
        if (session()->has('tab')) 
        {
            session()->forget('tab');
        }

        session(["tab"=>$request->input('tab')]);

        return response()->json("ok");
    } 

    public function saveDocument(Request $request)
    {
        $file = $request->file('file-document');
        $rDocument = $request->input('document-type');
        $alumnData = selectSicoes("Alumno","AlumnoId",current_user()->id_alumno);
        $path = "documentos/".$alumnData[0]["Matricula"];
        $document_type = DocumentType::find($rDocument);

        if ($file->getClientOriginalExtension()!="pdf") {
            session()->flash("messages","warning|El documento no tiene el formato requerido");
            return redirect()->back();
        }
        
        try {
            mkdir($path, 0755); 
        } catch(\ErrorException $e) {
        }

        $documentName = current_user()->name."_".$document_type->name.".".$file->getClientOriginalExtension();

        if (!file_exists($path."/".$documentName)) {
            $file->move($path, $documentName);
            $document = new Document();
        } else {
            unlink($path."/".$documentName);
            $file->move($path, $documentName);
            $document = Document::where("route","=",$path."/".$documentName)->first();
            if(!$document) {
                $document = new Document();
            }
        }

        $document->description = "Documento de inscripción";
        $document->route = "/".$path."/".$documentName;
        $document->status = 1;
        $document->PeriodoId = selectCurrentPeriod();
        $document->alumn_id = current_user()->id;
        $document->type = 1;
        $document->document_type_id = $document_type->id;
        $document->save();

        session()->flash("messages","success|El documento se guardo con exito");
        return redirect()->back();
    }
}