<?php namespace App\Http\Controllers\Alumn;

use Illuminate\Http\Request;
use Mpdf\Mpdf;
use App\Http\Controllers\Controller;
use App\Models\Alumns\Debit;
use App\Models\Alumns\Document;
use App\Models\Alumns\DocumentType;
use App\Models\PeriodModel;
use App\Models\Sicoes\Alumno;
use Auth;

class PdfController extends Controller
{
    public function index()
    {
        return view('Alumn.pdf.index');
    }

    public function showDocuments(Request $request)
    {
        $filter = $request->get('search') && isset($request->get('search')['value'])?$request->get('search')['value']:false;
        $start = $request->get('start');
        $length = $request->get('length');
        $current_user = current_user();

        $query = Document::select('document.*','document_type.name')->join('document_type','document.document_type_id','document_type.id')->where('alumn_id',$current_user->id)->orderByDesc("document.id");

        $total = $query->count();
        $filtered = $query->count();

        if ($filter) {
            $query = $query->where(function($query) use ($filter){
                $query->orWhere('document_type.name', 'like', '%'. $filter .'%')
                    ->orWhere('document.description', 'like', '%'. $filter . '%')
                    ->orWhere('document.PeriodoId', 'like', '%'. $filter . '%');
            });
            $filtered = $query->count();
        }

        $query->skip($start)->take($length)->get();

        $documents = $query->get();
        $res = [];
        foreach($documents as $key => $value)
        {
            $buttons = "";
            if ($value->payment == 0) {
                $buttons .= "<div class='btn-group'><button class='btn btn-danger btnCancelDocument' title='Imprimir' id_document='".$value->id."'>
                    Cancelar</button>
                    </div>";
            } else {
                try {
                    $buttons .= "<div class='btn-group'><a class='btn btn-primary reload' target='_blank' href='".route($value->route,$value)."' title='Imprimir'>
                    Imprimir</a>
                    </div>";
                } catch(\Exception $e) {
                    $buttons .= "<div class='btn-group'><a class='btn btn-primary printDocument' target='_blank' href='/".$value->route."' title='Imprimir'>
                    Imprimir</a>
                    </div>";
                }
            }

            $period = PeriodModel::find($value->PeriodoId);

            array_push($res,[
                "#" => ($key+1),
                "Nombre" => $value->name,
                "Descripción" => $value->description,
                "Periodo" => $period->clave,
                "Fecha de creacion" => $value->created_at,
                "Acciones" => $buttons
            ]);
        }

        return response()->json([
            "recordsTotal" => $total,
            "recordsFiltered" => $filtered,
            "data" => $res
        ]);
    }

    public function getOfficialDocument(DocumentType $documentType) {
        try {
            $user = current_user();
            if ($documentType->type == 1) {
                $debit = new Debit();
                $debit->debit_type_id = 5;
                $debit->description = 'Documento oficial unisierra';
                $debit->amount = $documentType->cost;
                $debit->admin_id = 2;
                $debit->id_alumno = $user->sAlumn->AlumnoId;
                $debit->status = 0;
                $debit->period_id = getConfig()->period_id;
                $debit->save();
                $debit->setForeignValues();
                $debit->generateDocument($user, $documentType);
                $debit->addNotify($user->id, "users", "alumn.debit");  

                session()->flash("messages", "success|Se agrego a tu lista de documentos, no olvides pagar tu nuevo adeudo");
                return redirect()->back();
            } else {
                session()->flash("messages", "error|Este documento no se puede agregar");
                return redirect()->back();
            }
        } catch(\Exception $e) {
            dd($e);
            session()->flash("messages", "error|No fue posible generar el adeudo");
            return redirect()->back();
        }
    }

    public function getGenerarCedula(Request $request, $id)
    {
        $current_user = Auth::guard("alumn")->user();

        $document = Document::where([["alumn_id","=",$current_user->id],["id","=",$id]])->first();
        $currentPeriod = selectCurrentPeriod();

        if ($document==null) {
            return redirect()->back();
        }

        try {
            $alumno = Alumno::where("AlumnoId", $current_user->id_alumno)->first();

            $inscripcion = Inscripcion::where("AlumnoId", $current_user->id_alumno)
                                        ->orderBy("InscripcionId", "desc")
                                        ->first();

            $group = Grupo::find($inscripcion->EncGrupoId);
        } catch(\Exception $e) {
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
        // return redirect(Request::url());     
    }

    public function getGenerarConstancia(Request $request, $id)
    {      
        $current_user = current_user();

        $document = Document::where([["alumn_id","=",$current_user->id],["id","=",$id]])->first();

        if ($document==null) {
            return redirect()->back();
        }

        try
        {
            $alumno = selectSicoes("Alumno","AlumnoId",$current_user->id_alumno)[0];
        }
        catch(\Exception $e)
        {
            session()->flash("messages","error|Ocurrio un problema, no se encontro tu registro de sicoes");
            return redirect()->back();
        }
 
        $html = view('Alumn.pdf.constancia')->with('alumno', $alumno)->render();
        $namefile = 'CONSTANCIA'.time().'.pdf';

        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);

        $document->save();
       
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($html);
        $mpdf->Output($namefile,"I");   
    }

    public function getGenerarFicha(Request $request, $tipo, $accion, $pago)
    {
        $query = [["id_alumno","=",Auth::guard("alumn")->user()->id_alumno],["status","=","0"]];
        $total = Debit::where($query)->get()->sum("amount");        
        $current_user = current_user();

        $alumno = Alumno::find($current_user->id_alumno);
        
        $data['tipo'] = $tipo;                   

        $html = view('Alumn.pdf.ficha',[
            'alumno' => $alumno,
            'deuda_total' => $total
        ])->render();

        $namefile = 'Ficha'.time().'.pdf';
 
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
        $path = "documentos/" . current_user()->sAlumn->Matricula;
        $document_type = DocumentType::find($rDocument);

        if ($file->getClientOriginalExtension() != "pdf") {
            session()->flash("messages","warning|El documento no tiene el formato requerido");
            return redirect()->back();
        }
        
        $documentName = current_user()->name."_".$document_type->name.".".$file->getClientOriginalExtension();

        if (file_exists("/".$path."/".$documentName)) {
            unlink("/".$path."/".$documentName);
        }

        $file->move($path, $documentName);
        $document = Document::where("route", $path."/".$documentName)->first();
        if(!$document) {
            $document = new Document();
        }

        $document->description = "Documento de inscripción";
        $document->route = "/".$path."/".$documentName;
        $document->status = 1;
        $document->PeriodoId = getConfig()->period_id;
        $document->alumn_id = current_user()->id;
        $document->type = 1;
        $document->document_type_id = $document_type->id;
        $document->save();

        session()->flash("messages","success|El documento se guardo con exito");
        return redirect()->back();
    }

    public function deleteDocument($id) {
        try {
            $document = Document::find($id);
            Document::destroy($id);
            Debit::destroy($document->id_debit);
            session()->flash("messages","success|Se elimino con exito");
            return redirect()->back();
        } catch(\Exception $e) {
            session()->flash("messages","error|No te podras deshacer de este adeudo");
            return redirect()->back();
        }
    }
}