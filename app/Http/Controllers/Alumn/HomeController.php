<?php

namespace App\Http\Controllers\Alumn;

use Auth;
use App\Models\Alumns\Debit;
use Illuminate\Http\Request;
use App\Models\Alumns\Ticket;
use App\Models\Alumns\Document;
use App\Models\AdminUsers\Problem;
use App\Http\Controllers\Controller;
use App\Models\Sicoes\Alumno;
use App\Library\Log;
use DB;

class HomeController extends Controller
{
    private $logger;

    public function callAction($method, $parameters)
    {
        $this->logger = new Log(App\Http\Controllers\Alumn\HomeController::class);
        return parent::callAction($method, $parameters);
    }

    public function index()
    {
        $this->logger->info("Hola mundo logger");
        $user = current_user();

        switch ($user->inscripcion) {
            case 0:
                $status = 'Inscribirse';
                break;
            case 1:
                $status = 'Realizar pago';
                break;
            case 2:
                $status = 'Validación';
                break;
            case 3:
                $status = 'Carga Académica';
                break;
            case 4:
                $status = 'Inscrito';
                break;
            default:
                $status = "Inscribirse";
                break;
        }

        //documentos
        $documents = Document::where("alumn_id","=",$user->id)->get();

        //adeudos
        $query = [["id_alumno","=",$user->id_alumno],["status","=","0"],["debit_type_id","<>", 1]];
        $debit = Debit::where($query)->get();
        $total = $debit->count("amount");


        //tickets
        $tickets = Ticket::where("alumn_id","=",$user->id)->get();

        return view('Alumn.home.index')->with([
            "status" => $status,
            'documents' => $documents,
            'debits' => $total,
            'tickets' => $tickets
        ]);
    }

    public function saveProblem(Request $request)
    {
        $request->validate([
            'text' => 'required',
        ]);
        try
        {
            $problem = new Problem();
            $problem->text = $request->input("text");
            $problem->alumn_id = Auth::guard("alumn")->user()->id;
            $problem->save();
            session()->flash("messages","success|Se envio el problema correctamente");
            return redirect()->back();
        }
        catch(\Exception $e)
        {
            session()->flash("messages","error|Tenemos problemas con enviar su problema");
            return redirect()->back();
        }
    }
}