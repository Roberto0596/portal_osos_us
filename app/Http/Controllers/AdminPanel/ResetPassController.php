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

            $buttons = "<div class='btn-group'><button style='color:white' class='btn btn-danger custom resetPassword' id = '".$value->id_user."' token = '".csrf_token()."'><i class='fa fa-check' title='Aprobar'></i></button></div>";

        
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
            $user = User::find( $request->id);
            $newPass = generatePasssword();                
            $user->password = bcrypt($newPass);                
            $user->save();
            //creamos un arreglo para pasrlo  a la vista que se enviara al correo  
            $data = [
                'name' => $user->name,
                'new_pass' => $newPass,
            ];
            $subject = 'Restablecer Cuenta';
            Mail::to($user->email)->queue(new ResetPassword($subject,$data));             
            // Mail::send('AdminPanel.RequestPass.email-template',['data' => $data], function ($msj) use ($user)
            // {
            //     $msj->subject();
            //     $msj->to($user->email);
                
            // });  
            //una vez enviado el email se borra el registro
            RequestPass::where('id_user' , $request->id)->delete();
            return response()->json('ok');                  
        }
        catch(\Exception $e)
        {
            dd($e);
            return response()->json("error");
        }
	}
}

