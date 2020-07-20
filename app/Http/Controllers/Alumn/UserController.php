<?php

namespace App\Http\Controllers\Alumn;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Alumns\User;
use App\Models\Alumns\Notify;
use Input;
use Auth;
use Illuminate\Support\Collection;
use App\Http\Requests\CreateUserRequest;
use App\Models\Website\Pending;

class UserController extends Controller
{
	public function index()
	{
        $current_id = Auth::guard('alumn')->user()->id;
        $current_user = User::find($current_id);
		return view('Alumn.user.index')->with(["user"=>$current_user]);
	}

    public function notify(Request $request)
    {
        $query = [["alumn_id","=",$request->input('AlumnId')],["status","=","0"]];
        $notifys = Notify::where($query)->get();
        return response()->json($notifys);
    }

    public function seeNotify($route,$id)
    {
        $notify = Notify::find($id);
        $notify->status = 1;
        $notify->save();
        return redirect()->route($route);
    }

    public function save(Request $request, User $user)
    {
        if ($request->input('password')!=null)
        {
            $user->password = bcrypt($request->input('password'));
        }

        if(isset($_FILES['newPicture']) && $_FILES["newPicture"]["name"]!="")
        {
            if ($user->photo == "img/alumn/default/default.png")
            {
                $routePicture = ctrCrearImagen($_FILES["newPicture"],$user->id,"alumn",100,120,false);
                $user->photo = $routePicture;
            }
            else
            {
                unlink($user->photo);

                $routePicture = ctrCrearImagen($_FILES["newPicture"],$user->id,"alumn",100,120,true);
                $user->photo = $routePicture;
            }
        }

        $user->name = $request->input("name");
        $user->lastname = $request->input("lastname");
        $user->save();
        session()->flash("messages","success|Datos guardados correctamente");
        return redirect()->route("alumn.user");
    }
}