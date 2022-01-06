<?php namespace App\Http\Controllers\Alumn;

use Illuminate\Http\Request;
use Mpdf\Mpdf;
use App\Http\Controllers\Controller;
use App\Models\Alumns\Debit;
use App\Models\Alumns\Document;
use App\Models\Alumns\DocumentType;
use App\Models\PeriodModel;
use App\Models\Sicoes\Alumno;
use App\Models\Sicoes\Inscripcion;
use App\Models\Sicoes\EncGrupo;
use App\Models\Sicoes\Escuela;
use Auth;

class PdfController extends Controller
{
    private $current_user;

    public function callAction($method, $parameters)
    {
        $this->current_user = current_user();
        return parent::callAction($method, $parameters);
    }

    public function index()
    {
        return view('Alumn.pdf.index');
    }

    public function showDocuments(Request $request)
    {
        $filter = $request->get('search') && isset($request->get('search')['value'])?$request->get('search')['value']:false;
        $start = $request->get('start');
        $length = $request->get('length');

        $query = Document::where('alumn_id',$this->current_user->id)
                            ->orderByDesc("id")
                            ->with("documentType")
                            ->with("period");

        if ($filter) {
            $query = $query->where(function($query) use ($filter){
                $query->orWhere('document_type.name', 'like', '%'. $filter .'%')
                    ->orWhere('document.description', 'like', '%'. $filter . '%')
                    ->orWhere('document.PeriodoId', 'like', '%'. $filter . '%');
            });
        }

        $filtered = $query->count();

        $query->skip($start)->take($length)->get();

        return response()->json([
            "recordsTotal" => $query->count(),
            "recordsFiltered" => $filtered,
            "data" => $query->get()
        ]);
    }

    public function redirectToDocument(Request $request) {
        try {
            $document = Document::find($request->get("id"));
            return redirect()->route($request->get("route"),$document);
        } catch(\Exception $e) {
            return redirect($request->get("route"));
        }
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
            session()->flash("messages", "error|No fue posible generar el adeudo");
            return redirect()->back();
        }
    }

    public function getGenerarCedula(Request $request, $id)
    {
        $document = Document::where([["alumn_id","=",$this->current_user->id],["id","=",$id]])->first();

        if ($document == null) {
            return redirect()->back();
        }

        try {
            $inscripcion = Inscripcion::where("AlumnoId", $this->current_user->id_alumno)
                                        ->orderBy("InscripcionId", "desc")
                                        ->first();
            $group = EncGrupo::find($inscripcion->EncGrupoId);
        } catch(\Exception $e) {
            session()->flash("messages","error|Ocurrio un problema, no se encontro tu registro de sicoes");
            return redirect()->back();
        }   

        try {
            $bachiller = Escuela::find($this->current_user->sAlumn->EscuelaProcedenciaId);
        } catch (\Exception $e) {
            $bachiller = (Object) ['Nombre' => 'Sin dato'];
        }       

        $html = view('Alumn.pdf.generar', [
            'alumno' => $this->current_user->sAlumn, 
            "lugar_nacimiento" => $this->current_user->sAlumn->MunicipioNacRelation()->first(),
            'direccion' => $this->current_user->sAlumn->getRecidencia(),
            'datos_escolares' => [
                'carrera' => $this->current_user->sAlumn->getCarrera(),
                'periodo' => selectCurrentPeriod()->clave,
                'semestre' => $inscripcion->Semestre,
                'escuela_procedencia' => $bachiller->Nombre,
                'grupo' => $group->Nombre
            ]
        ])->render();
        
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
        $document = Document::where([["alumn_id","=",$this->current_user->id],["id","=",$id]])->first();

        if ($document == null) {
            return redirect()->back();
        }
 
        $html = view('Alumn.pdf.constancia')->with('alumno', $this->current_user->sAlumn)->render();
        $namefile = 'CONSTANCIA'.time().'.pdf';

        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);

        $document->save();
       
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($html);
        $mpdf->Output($namefile,"I");   
    }

    public function getGenerarFicha(Request $request, $tipo, $accion, $pago)
    {
        $query = [["id_alumno","=", $this->current_user->id_alumno],["status","=","0"]];
        $total = Debit::where($query)->get()->sum("amount");        
        
        $data['tipo'] = $tipo;                   

        $html = view('Alumn.pdf.ficha',[
            'alumno' => $this->current_user->sAlumn,
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