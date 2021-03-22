<?php namespace App\Http\Controllers\DepartamentPanel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Logs\Equipment;
use App\Models\Logs\TempUse;
use App\Models\Logs\ClassRoom;
use App\Models\Sicoes\Alumno;
use App\Models\Alumns\User;
use Carbon\Carbon;

class ClassRoomController extends Controller {

	public function index() {
        $classrooms = ClassRoom::where("area_id", current_user('departament')->area->id)->get();
		return view("DepartamentPanel.logs.classroom.index")->with(["instances" => $classrooms]);
	}

    public function create() {
        $instance = new ClassRoom();
        return view("DepartamentPanel.logs.classroom.form")->with(["instance" => $instance]);
    }

    public function edit($id) {
        $instance = ClassRoom::find($id);
        return view("DepartamentPanel.logs.classroom.form")->with(["instance" => $instance]);
    }

    public function delete($id) {
        $instance = ClassRoom::destroy($id);
        session()->flash("messages", "success|Eliminado correctamente");
        return redirect()->back();
    }

	public function save(Request $request, ClassRoom $instance) {
		try {
            $request->validate([
                "name" => "required",
                "status" => "required"
            ]);

            $user = current_user('departament');

            $validateName = ClassRoom::where("name", $request->get('name'))
                            ->where('area_id', $user->area_id)
                            ->first();

            if ($validateName) {
                session()->flash("messages", "warning|Este nombre ya está registrado");
                return redirect()->back();
            }

            if (!$instance->id) {
                $nextNum = ClassRoom::where("area_id", $user->area_id)->orderBy("num", "desc")->first();
                $instance->num = $nextNum->num + 1;
                $instance->code = "CC".$instance->num;
            }

            $instance->name = ucfirst($request->get("name"));
            $instance->status = $request->get("status");
            $instance->area_id = $user->area_id;
            $instance->save();
            session()->flash("messages", "success|Guardado correcto");
            return redirect()->route('departament.logs.classrooms.index');
        } catch(\Exception $e) {
            dd($e);
        	session()->flash("messages", "error|Ooops, tuvimos un problema");
            return redirect()->back()->withInputs();
        }
	}
}