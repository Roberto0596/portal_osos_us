<?php

namespace App\Http\Controllers\FinancePanel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AdminUsers\AdminUser;
use Input;
use Auth;

class UserController extends Controller
{
    public function index()
	{
        $current_id = Auth::guard('finance')->user()->id;
        $current_user = AdminUser::find($current_id);
		return view('FinancePanel.user.index')->with(["user"=>$current_user]);
    }

    public function save(Request $request, AdminUser $user)
    {
        if ($request->input('password')!=null)
        {
            $user->password = bcrypt($request->input('password'));
        }

        if(isset($_FILES['newPicture']) && $_FILES["newPicture"]["name"]!="")
        {
            if ($user->photo == "img/computercenter/default/default.png")
            {
                $routePicture = ctrCrearImagen($_FILES["newPicture"],$user->id,"computercenter",100,120,false);
                $user->photo = $routePicture;
            }
            else
            {
                unlink($user->photo);

                $routePicture = ctrCrearImagen($_FILES["newPicture"],$user->id,"computercenter",100,120,true);
                $user->photo = $routePicture;
            }
        }

        $user->name = $request->input("name");
        $user->email = $request->input("email");
        $user->lastname = $request->input("lastname");
        $user->save();
        session()->flash("messages","success|Datos guardados correctamente");
        return redirect()->route("finance.user");
    }
}
