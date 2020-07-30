<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminUsers\RequestPass;
use App\Models\Alumns\User;
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

        //se busca el usuario por el id
        $user = User::find( $request->id);

       
       

		if($user != null){

            //se genera una constraseña nueva
            $newPass = $this->generatePasssword();
            
            //se encripta la contraseña
            $user->password = bcrypt($newPass);
            
            //se guarda en la bd
			$user->save();

            //creamos un arreglo para pasrlo  a la vista que se enviara al correo
			$data = [
				'name' => $user->name,
				'new_pass' => $newPass,
            ];

            //se envia el correo
            try{

               

                
                Mail::send('AdminPanel.RequestPass.email-template',['data' => $data], function ($msj) use ($user)
                {
                    $msj->subject('Restablecer Cuenta');
                    $msj->to($user->email);
                    
                });

                

               
                
            }catch(\Exception $e){
                return response()->json('error');

            }  

            //una vez enviado el email se borra el registro
            RequestPass::where('id_user' , $request->id)->delete();
            return response()->json('ok');

		}else{
            return response()->json('error');
		}
	}

	private function generatePasssword()
    {
        $letters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '1234567890';
        $password = '';
        for($i = 0; $i < 4; $i++){
            $randomIndexLetras = mt_rand(0,strlen($letters) - 1);
            $randomIndexNumbers = mt_rand(0,strlen($numbers) - 1);
            $password = $password.$letters[$randomIndexLetras].$numbers[$randomIndexNumbers];  
        }
        return $password;
    }

}

