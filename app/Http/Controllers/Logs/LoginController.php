<?php namespace App\Http\Controllers\Logs;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Logs\Equipment;
use App\Models\Logs\TempUse;
use App\Models\Logs\ReportEquipment;
use App\Models\Sicoes\Alumno;
use App\Models\Alumns\User;
use Carbon\Carbon;
use Auth;

class LoginController extends Controller
{
	public function index()
	{
		return view('Logs.login');
    }

    public function getQuickBooking(Request $request) {

        $request->validate([
            "enrollment" => "required"
        ]);

        $alumnSicoes = Alumno::where("Matricula", $request->get('enrollment'))->first();

        if (!$alumnSicoes) {
            return response()->json([
                "status" => null, 
                "id_equipment" => null, 
                "missingRegister" => true,
                "thereIsRecord" => false
            ]);
        }  

        $alumnPortal = User::where("id_alumno", $alumnSicoes->AlumnoId)->first();

        if (!$alumnPortal) {
            return response()->json([
                "status" => null, 
                "id_equipment" => null, 
                "missingRegister" => true,
                "thereIsRecord" => false
            ]);
        }

        $auth = current_user("log_auth");

        $tempUse = TempUse::where("alumn_id", $alumnPortal->id)
                    ->where('area_id', $auth->area_id)
                    ->first();

        if ($tempUse) {
            return response()->json([
                "status" => null, 
                "id_equipment" => $tempUse->equipment_id, 
                "missingRegister" => false,
                "thereIsRecord" => true,
                "id_temp" => $tempUse->id
            ]);
        }

        $equipment = Equipment::leftJoin("classroom as cr", "equipment.classroom_id", "cr.id")
                    ->where("cr.area_id", $auth->area_id)
                    ->where("cr.status", "0")
                    ->where("equipment.status", "0")
                    ->select("equipment.*")->first();

        if ($equipment) {
            return response()->json([
                "status" => "success", 
                "num" => $equipment->num,
                "id_equipment" => $equipment->id, 
                "missingRegister" => false,
                "thereIsRecord" => false
            ]);
        } else {
            return response()->json([
                "status" => "failed", 
                "num" => null,
                "id_equipment" => , 
                "missingRegister" => false,
                "thereIsRecord" => false
            ]);
        }
    }

    public function saveQuickBooking(Request $request) {
        try {
            $alumnSicoes = Alumno::where("Matricula", $request->get('enrollment'))->first();
            $alumnPortal = User::where("id_alumno", $alumnSicoes->AlumnoId)->first();

            date_default_timezone_set('America/Hermosillo');

            $auth = current_user("log_auth");

            $instance = new TempUse();
            $instance->equipment_id = $request->get("id_equipment");
            $instance->alumn_id = $alumnPortal->id;
            $instance->enrollment = $alumnSicoes->Matricula;
            $instance->entry_time = date('H:i:s');
            $instance->area_id = $auth->area_id;
            $instance->save();

            $equipment = Equipment::find($instance->equipment_id);
            $equipment->status = 1;
            $equipment->save();
            return response()->json(["status" => "success", "message" => "Reservación creada, por favor pasa a tu computadora."]);
        } catch(\Exception $e) {
            return response()->json(["status" => "error", "message" => "No fue posible reservar el equipo."]);
        }

    }

    public function closeBooking(Request $request) {
        $id_temp = $request->get('id_temp');
        $tempUse = TempUse::find($id_temp);
        $equipment = Equipment::find($tempUse->equipment_id);
        $equipment->status = 0;
        $equipment->save();

        //insert report
        $this->insertReportRegister([
            "equipment_id" => $equipment->id,
            "alumn_id" => $tempUse->alumn_id,
            "entry_time" => $tempUse->entry_time
        ]);
        
        $tempUse->delete();
    }

    public function insertReportRegister($array) {
        $report = new ReportEquipment();
        $report->equipment_id = $array["equipment_id"];
        $report->alumn_id = $array["alumn_id"];
        $report->entry_time = $array["entry_time"];

        date_default_timezone_set('America/Hermosillo');
        $report->departure_time = date('H:i:s');
        $report->save();
    }
}