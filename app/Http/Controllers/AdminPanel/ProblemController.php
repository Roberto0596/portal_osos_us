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
            $disabled = $value->status==0?"btnFixed":"";
            $buttons="<div class='btn-group'>
                <button class='btn btn-info btnDescription' idProblem = '".$value->id."' data-toggle='modal' data-target='#modalProblems' title='Ver descripción'><i class='fa fa-eye'></i></button></div>
                <button class='btn btn-warning ".$disabled."' idProblem = '".$value->id."' title='Corregir'><i class='fa fa-th' style='color:white'></i></button></div>
                <button class='btn btn-danger btnDelete' idProblem = '".$value->id."' title='Eliminar'><i class='fa fa-times'></i></button></div>";

            if ($value->status==0)
            {
            	$status = "Sin corregir";
            }
            else
            {
            	$status = "Corregido";
            }

            $portalUserData = selectTable("users","id",$value->alumn_id,1);
            $alumnData = selectSicoes("Alumno","AlumnoId", $portalUserData->id_alumno)[0];
            array_push($res["data"],[
                (count($problems)-($key+1)+1),
                $alumnData["Matricula"],
                $portalUserData->name,
                $alumnData["Telefono"],
                $portalUserData->email,
                $status,
                $value->created_at,
                $buttons
            ]);
        }
        return response()->json($res);
    }

    public function delete($id)
    {
        try
        {
           $delete = Problem::destroy($id);
           session()->flash("messages","success|Problema eliminado con éxito");
           return redirect()->back();
        }
        catch(\Exception $e)
        {
            session()->flash("messages","error|Tuvimos problemas eliminando el problema");
           return redirect()->back();
        }        
    }

    public function fixed($id)
    {
        try
        {
           $delete = Problem::find($id);
           $delete->status = 1;
           $delete->save();
           session()->flash("messages","success|El problema ya no causara mas problemas");
           return redirect()->back();
        }
        catch(\Exception $e)
        {
           session()->flash("messages","error|Tuvimos problemas eliminando el problema");
           return redirect()->back();
        }        
    }
}