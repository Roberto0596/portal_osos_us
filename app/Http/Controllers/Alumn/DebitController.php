<?php

namespace App\Http\Controllers\Alumn;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Alumns\User;
use App\Models\Alumns\Document;
use App\Models\Alumns\Debit;
use App\Models\AdminUsers\AdminUser;
use Input;
use Auth;

class DebitController extends Controller
{
	public function index()
	{
        return view('Alumn.debit.index');
	}

    public function show(Request $request)
    {
        $current_user = Auth::guard("alumn")->user();
        $res = [ "data" => []];
        $query = [["id_alumno","=",$current_user->id_alumno],["status","=","0"]];
        $debits = Debit::where($query)->get();

        foreach($debits as $key => $value)
        {
            $adminUser = AdminUser::find($value->admin_id);
            $buttons="<div class='btn-group'><button class='btn btn-success'><i class='fas fa-dollar-sign'></i></button></div>";
  
            array_push($res["data"],[
                (count($debits)-($key+1)+1),
                $value->concept,
                "$".number_format($value->amount,2),
                $adminUser->name,
                $current_user->name,
                $value->created_at,
                $buttons
            ]);
        }

        return response()->json($res);
    }

    public function edit($id)
    {
    }

    public function delete($id)
    {
    }

    public function save(Request $request, Categories $categorie) 
    {
    }
}