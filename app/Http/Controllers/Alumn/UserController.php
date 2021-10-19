<?php

namespace App\Http\Controllers\Alumn;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Alumns\User;
use App\Models\Alumns\Notify;
use App\Http\Requests\CreateUserRequest;
use App\Models\Website\Pending;
use Auth;

class UserController extends Controller
{
	public function index()
	{
        $current_user = current_user();
		return view('Alumn.user.index')->with(["user" => $current_user]);
	}

    public function notify(Request $request)
    {
        return response()->json(Notify::where("id_target", current_user()->id)
                                ->where("target", "users")
                                ->where("status", "0")->get());
    }

    public function seeNotify($route,$id)
    {
        $notify = Notify::where("id_target", $id)->where("target", "users")->get();
        foreach ($notify as $key => $value) {
            $value->status = 1;
            $value->save();
        }
        return redirect()->route($route);
    }

    public function save(Request $request, User $user)
    {
        if ($request->input('password')!=null)
        {
            $user->password = bcrypt($request->input('password'));
        }

        if ($request->hasFile("newPicture")) {
            $file = $request->file("newPicture");
            $user->photo = upload_image($file, "alumn", current_user()->id);
        }

        $user->name = $request->input("name");
        $user->lastname = $request->input("lastname");
        $user->save();
        session()->flash("messages","success|Datos guardados correctamente");
        return redirect()->route("alumn.user");
    }
}