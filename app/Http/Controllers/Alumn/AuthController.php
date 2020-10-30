<?php namespace App\Http\Controllers\Alumn;

use Auth;
use Input;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Alumns\User;
use App\Models\AdminUsers\RequestPass;
use App\Models\Alumns\PasswordRequest;
use Mail;
use App\Mail\ResetPassword;

class AuthController extends Controller
{
    public function login() 
    {   
        if (Auth::guard("alumn")->check())
        {
            return redirect()->route('alumn.home');
        }  
        return view('Alumn.auth.login');
    }

    public function postLogin(Request $request)
    {
        $email = $request->input('email');
        $pass = $request->input('password');

        $user = User::where('email', "=" ,$email)->first();

        if (!$user) {
            session()->flash('messages', 'error|No Existe un usuario con ese correo');
            return redirect()->back();
        }

        if (Auth::guard('alumn')->attempt(['email' => $email, 'password' => $pass],$request->get('remember-me', 0)))
        {
            return redirect()->route('alumn.home');
        }
        session()->flash('messages', 'error|El password es incorrecto');
        return redirect()->back();
    }

    public function logout(Request $request)
    {
        Auth::guard('alumn')->logout();
        session()->flush();
        return redirect('/');
    }

    public function requestRestorePass() 
    {       
        return view('Alumn.auth.request-pass');
    }

    public function sendRequest(Request $request)
    {
        //buscamos el usuario por el email
        $user = User::where('email', '=', $request->email)->first();
        if(!$user)
        {
            session()->flash("messages","error|No existe un usuario con este correo");
            return redirect()->back();
        }     
        try
        {            
            $RequestPass = new PasswordRequest();
            $RequestPass->token = uniqid();
            $RequestPass->alumn_id = $user->id;
            $RequestPass->save(); 

            $data = [
                'name' => $user->name,
                'new_pass' => "http://alumnos.unisierra/restore-pass/".$RequestPass->token,
            ];

            $subject = 'Restablecer Cuenta';
            $to = $user->id_alumno != null ? [$user->email, strtolower($user->getSicoesData()["Email"])] : $user->email;

            Mail::to($to)->queue(new ResetPassword($subject,$data));  

            session()->flash("messages","success|Se envio un link a tu correo");
            return redirect()->route("alumn.login");
        }
        catch (\Exception $e)
        {
            session()->flash("messages","error|Ya tienes una solicitud enviada");
            return redirect()->back();
        }    
    } 
}