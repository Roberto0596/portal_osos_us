<?php namespace App\Http\Controllers\DepartamentPanel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Logs\Equipment;
use App\Models\Logs\TempUse;
use App\Models\Logs\ClassRoom;
use App\Models\Sicoes\Alumno;
use App\Models\Alumns\User;
use Carbon\Carbon;

class EquipmentController extends Controller {

	public function index() {
        $classrooms = ClassRoom::where("area_id", current_user('departament')->area->id)->orderBy("num")->get();
		return view("DepartamentPanel.logs.equipment.index")->with(["classrooms" => $classrooms]);
	}

    public function delete($id) {
        $instance = Equipment::destroy($id);
        session()->flash("messages", "success|Eliminado correctamente");
        return redirect()->back();
    }

    public function equipment(Request $request) {
        $equipment = Equipment::leftJoin("temp_use as t", "equipment.id", "=", "t.equipment_id")
                    ->leftJoin("users as u", "t.alumn_id", "u.id")
                    ->select("equipment.*", "t.id as temp_use", "u.id as alumn")
                    ->where("equipment.id", $request->get('id'))
                    ->first();

        return response()->json($equipment);
    }

    public function alumnData(Request $request) {
        $equipment = Equipment::leftJoin("temp_use as t", "equipment.id", "=", "t.equipment_id")
                    ->leftJoin("users as u", "t.alumn_id", "u.id")
                    ->select("u.*")
                    ->where("equipment.id", $request->get('id'))
                    ->first();
        $info = Alumno::where("AlumnoId", $equipment->id_alumno)->first();

        return response()->json($info);
    }

    public function fillOrQuit(Request $request) {
        $equipment = Equipment::find($request->get('id_equipment'));

        if ($request->has('value')) {
            $equipment->status = $request->get("value");
        } else {

            if ($equipment->status > 1) {
                session()->flash("messages", "warning|Equipo fuera de servicio");
                return redirect()->back();
            }
            $equipment->status = $equipment->status == 0 ? 1 : 0;
        }

        $equipment->save();
        session()->flash("messages", "success|Cambios guardados con exito");
        return redirect()->back();
    }

	public function save(Request $request) {
		try {
            $request->validate([
                "classroom_id" => "required",
                "num" => "required"
            ]);

            $validate = Equipment::where("num", $request->get('num'))
                        ->where('classroom_id', $request->get('classroom_id'))
                        ->first();
            if ($validate) {
                session()->flash("messages", "warning|No se pueden duplicar los numeros en el mismo salón");
                return redirect()->back();
            }
            $instance = new Equipment();
            $instance->classroom_id = $request->get('classroom_id');
            $instance->code = "CC".$request->get('num');
            $instance->num = $request->get('num');
            $instance->status = $request->get('status');
            $instance->save();
            session()->flash("messages", "success|Guardado correcto");
            return redirect()->back();
        } catch(\Exception $e) {
        	session()->flash("messages", "error|Ooops, tuvimos un problema");
            return redirect()->back()->withInputs();
        }
	}
}