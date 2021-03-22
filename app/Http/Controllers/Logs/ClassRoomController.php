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
		return view("Logs.classrooms")->with(["classrooms" => $classrooms]);
	}

	public function save(Request $request) {
		try {
            $alumnSicoes = Alumno::where("Matricula", session()->get('enrollment'))->first();
            $alumnPortal = User::where("id_alumno", $alumnSicoes->AlumnoId)->first();

            $auth = current_user("log_auth");

            $instance = new TempUse();
            $instance->equipment_id = $request->get("id_equipment");
            $instance->alumn_id = $alumnPortal->id;
            $instance->enrollment = $alumnSicoes->Matricula;
            $instance->entry_time = $request->get('time');
            $instance->area_id = $auth->area_id;
            $instance->save();

            $equipment = Equipment::find($instance->equipment_id);
            $equipment->status = 1;
            $equipment->save();
            session()->flash("messages", "success|Reservado correcto, pasa a tu computadora");
            return redirect()->route("logs.login");
        } catch(\Exception $e) {
        	session()->flash("messages", "error|Ooops, tuvimos un problema");
            return redirect()->route("logs.login");
        }
	}
}