<?php

namespace App\Http\Controllers\AdminPanel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AdminUsers\AdminUser;
use App\Models\AdminUsers\Area;
use Auth;

class UsersController extends Controller
{
	public function index()
    {
        return view('AdminPanel.users.index');
    }

    public function create() {
        $areas = Area::all();
        $user = new AdminUser();
        return view('AdminPanel.users.form')->with(["areas" => $areas, "user" => $user]);
    }

    public function edit($id) {
        $areas = Area::all();
        $user = AdminUser::find($id);
        return view('AdminPanel.users.form')->with(["areas" => $areas, "user" => $user]);
    }

    public function save(Request $request, AdminUser $user) {
        $data = $request->except('_token'); 

        foreach ($data as $key => $value) {
            if ($key == "password") {
                $user->$key = bcrypt($value);
            } else {
                $user->$key = $value;
            }            
        }

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $user->photo = createImage($photo, env('ADMIN_USERS_PATH'));
        }

        $user->first_time = 1;
        $user->save();
        session()->flash("messages","success|El usuario se guardo correctamente");
        return redirect()->route('admin.users');
    }
    
    public function delete($id) {
        try {
            AdminUser::destroy($id);
            session()->flash("messages","success|El usuario se borró correctamente");
            return redirect()->route('admin.users');
        }catch (\Exception $e) {
            session()->flash("messages","error|No se borro el usuario");
            return redirect()->back();
        } 
    }

    public function show()
    {
        $users = AdminUser::all();
        $res = [ "data" => []];

        foreach($users as $key => $value)
        {  
            $img = "<img src='".asset($value->photo)."' style='width:70px'>";
            $buttons = "<div class='btn-group'><a href='".route("admin.users.edit", $value->id)."'class='btn btn-warning'><i class='fa fa-eye' style='color:white'></i></a><button class='btn btn-danger btnDelete' user_id='".$value->id."'><i class='fa fa-times'></i></button></div>";
         
            array_push($res["data"],[
                (count($users)-($key+1)+1),
                $value->name,
                $value->lastname,
                $value->email,
                $img,
                selectTable("area","id",$value->area_id,"1")->name,
                $value->created_at,
                $buttons
            ]);
        }
        return response()->json($res);  
    }
}