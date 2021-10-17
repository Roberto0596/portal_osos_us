<?php

namespace App\Http\Controllers\AdminPanel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AdminUsers\AdminUser;
use App\Models\Alumns\Notify;
use Auth;

class UserController extends Controller
{
	public function index()
	{
        $current_user = current_user('admin');
		return view('AdminPanel.user.index')->with(["user"=>$current_user]);
    }

    public function save(Request $request, AdminUser $user)
    {
        if ($request->input('password')!=null)
        {
            $user->password = bcrypt($request->input('password'));
        }

        if ($request->hasFile("newPicture")) {
            $file = $request->file("newPicture");
            $user->photo = upload_image($file, "admin", current_user('admin')->id);
        }

        $user->name = $request->input("name");
        $user->email = $request->input("email");
        $user->lastname = $request->input("lastname");
        $user->save();
        session()->flash("messages","success|Datos guardados correctamente");
        return redirect()->route("admin.user");
    }

    public function notify(Request $request)
    {
        return response()->json(Notify::where("target", "admin")
                                ->where("status", "0")->get());
    }

    public function seeNotify($route)
    {
        $notify = Notify::where("target", "admin")->get();
        foreach ($notify as $key => $value) {
            $value->status = 1;
            $value->save();
        }
        return redirect()->route($route);
    }
}