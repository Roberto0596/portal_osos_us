<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminUsers\RequestPass;
use App\Models\Alumns\User;
use App\Mail\ResetPassword;
use Mail;

class ResetPassController extends Controller
{
    public function index()
	{
        return view('AdminPanel.RequestPass.index');       
    }

    public function show()
    {
        $requests = RequestPass::all();
        $res = [ "data" => []];       

        foreach($requests as $key => $value)
        {
            $user = User::find($value->id_user);

            $buttons = "<form class='from-password' method='post' action='".route("admin.reset.pass.save")."'>
                <div class='btn-group'>".csrf_field()."                    
                    <input type='hidden' name='user_id' value='".$value->id_user."'>
                    <button type='submit' style='color:white' class='btn btn-danger custom'><i class='fa fa-check' title='Aprobar'></i></button>
                </div>
                </form>"; 
        
            array_push($res["data"],[
                (count($requests)-($key+1)+1),
                $user->name,
                $user->lastname,
                $user->email,
                $value->created_at,
                $buttons
            ]);
        }

        return response()->json($res);  
    }

    public function sendPass(Request $request)
	{
        //se envia el correo
        try
        { 
            //se busca el usuario por el id
            $user = User::find($request->user_id);
            $newPass = generatePasssword();                
            $user->password = bcrypt($newPass);                
            $user->save();
            //creamos un arreglo para pasrlo  a la vista que se enviara al correo  
            $data = [
                'name' => $user->name,
                'new_pass' => $newPass,
            ];
            $subject = 'Restablecer Cuenta';
            $to = $user->id_alumno != null ? [$user->email, $user->getSicoesData()["Email"]] : $user->email;

            Mail::to($user->email)->queue(new ResetPassword($subject,$data));             

            //una vez enviado el email se borra el registro
            RequestPass::where('id_user' , $request->user_id)->delete();
            session()->flash("messages","success|Se envio la nueva contraseña con exito");
            addNotify("No olvides tu contraseña",$user->id,"alumn.home");
            return redirect()->back();                  
        }
        catch(\Exception $e)
        {
            session()->flash("messages","error|Algo salio mal");
            return redirect()->back();
        }
	}
}

