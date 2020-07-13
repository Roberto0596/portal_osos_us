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
}
