<?php namespace App\Http\Controllers\Logs;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Logs\Equipment;
use App\Models\Logs\TempUse;
use App\Models\Logs\ClassRoom;
use App\Models\Sicoes\Alumno;
use App\Models\Alumns\User;
use Carbon\Carbon;


class ClassRoomController extends Controller {

	public function index(Request $request) {
		$auth = current_user("log_auth");
		$classrooms = ClassRoom::where("area_id", $auth->area_id)->get();
		session(["enrollment" => $request->get('enrollment')]);
		return view("Logs.classroomsv2")->with(["classrooms" => $classrooms]);
	}

	public function save(Request $request) {
		try {
            $alumnSicoes = Alumno::where("Matricula", session()->get('enrollment'))->first();
            $alumnPortal = User::where("id_alumno", $alumnSicoes->AlumnoId)->first();

            $instance = Equipment::find($request->get("id_equipment"));

            $args = $request->except(["_token", "id_equipment"]);
            $args["id"] = $alumnPortal->id;
            $args["enrollment"] = $alumnSicoes->Matricula;
            $args["area_id"] = current_user("log_auth")->area_id;
            $instance->reserve($args);
            
            session()->flash("messages", "success|Reservado correcto, pasa a tu computadora");
            return redirect()->route("logs.login");
        } catch(\Exception $e) {
            dd($e);
        	session()->flash("messages", "error|Ooops, tuvimos un problema");
            return redirect()->route("logs.login");
        }
	}

    public function getEquipment($id) {
        $equipment = Equipment::find($id);
        return response()->json($equipment);
    }
}