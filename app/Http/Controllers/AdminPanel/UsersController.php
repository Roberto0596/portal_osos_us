<?php

namespace App\Http\Controllers\AdminPanel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AdminUsers\AdminUser;
use Auth;

class UsersController extends Controller
{
	public function index()
    {
        return view('AdminPanel.users.index');
    }

    public function show()
    {
        $users = AdminUser::all();
        $res = [ "data" => []];

        foreach($users as $key => $value)
        {  
            $img = "<img src='".asset($value->photo)."' style='width:40px'>";
            $buttons = "<div class='btn-group'><button class='btn btn-warning'><i class='fa fa-eye'></i></button></div>";
         
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