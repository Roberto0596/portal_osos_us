<?php

namespace App\Http\Controllers\AdminPanel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PeriodModel;
use App\Models\Alumns\Document;

class DocumentRequestController extends Controller
{
	public function index()
	{
		$documents = Document::where("id_debit", "<>", null)
                                ->where("status", 0)
                                ->orderByDesc("id")
                                ->get();
		return view('AdminPanel.document-request.index', ["documents" => $documents]);
    }

    public function fix($id) {
    	$document = Document::find($id);
    	if ($document) {
    		$document->status = 1;
            $document->save();
    		session()->flash("messages","success|Guardado exitoso");
    	} else {
    		session()->flash("messages","error|Ocurrio un error");
    	}
    	return redirect()->back();
    }

    public function upload(Request $request) {
    	$document = Document::find($request->get('idDocument'));
    	$alumnData = $document->alumn->sAlumn;
    	$file = $request->file('document');
    	
    	try {
    		$path = "documentos/".$alumnData->Matricula."/";
    		$documentName = uniqid().".".$file->getClientOriginalExtension();
			$file->move($path, $documentName); 
			$document->route = $path.$documentName; 
            $document->save();
			session()->flash("messages","success|Guardado exitoso");
    	} catch(\Exception $e) {
    		session()->flash("messages","error|Ocurrio un error");
    	}
    	return redirect()->back();
    }
}