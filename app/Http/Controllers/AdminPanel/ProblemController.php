<?php

namespace App\Http\Controllers\AdminPanel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AdminUsers\Problem;

class ProblemController extends Controller
{
	public function index()
	{
		return view('AdminPanel.problem.index');
    }

    public function seeProblem(Request $request)
    {
        $problem = Problem::find($request->input("problemId"));
        return response()->json($problem);
    }

    public function show()
    {
    	$problems = Problem::all();
        $res = [ "data" => []];
        foreach($problems as $key => $value)
        {
            $buttons="<div class='btn-group'>
                <button class='btn btn-danger btnDescription' idProblem = '".$value->id."' data-toggle='modal' data-target='#modalProblems' title='Ver descripción'><i class='fa fa-eye'></i></button></div>
                <button class='btn btn-warning btnFixed' title='Corregido'><i class='fa fa-th'></i></button></div>";

            if ($value->status==0)
            {
            	$status = "Sin corregir";
            }
            else
            {
            	$status = "Corregido";
            }
            
            array_push($res["data"],[
                (count($problems)-($key+1)+1),
                selectTable("users","id",$value->alumn_id,1)->name,
                $status,
                $value->created_at,
                $buttons
            ]);
        }
        return response()->json($res);
    }
}