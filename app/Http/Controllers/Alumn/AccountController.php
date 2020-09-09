<?php

namespace App\Http\Controllers\Alumn;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Alumns\User;
use Illuminate\Support\Collection;
use App\Http\Requests\CreateUserRequest;
use App\Models\Website\Pending;
use Input;
use Auth;

class AccountController extends Controller
{
	public function index() 
	{
        $step = 1;
        return view('Alumn.account.steps')->with(["step"=>$step]);
    }

    public function save(Request $request, $step)
    {
        //primer paso, buscar en la base de datos sicoes y validar que la matricula existe para posteriormente enviar al paso dos
        if ($step == 1)
        {
            $matricula = $request->input('matricula');
            $password = $request->input('password');
            $data = selectSicoes("alumno","Matricula",$matricula);
            $query = [["enrollment","=",$matricula],["status","=",0]];
            $validate = Pending::where($query)->get();

            if ($validate->isEmpty())
            {
                return view('Alumn.account.steps')->with(["step"=>1,"error"=>"Esta matricula no existe o ya estas registrado"]);
            }

            if($validate[0]->password != $password)
            {
                return view('Alumn.account.steps')->with(["step"=>1,"error"=>"Esa no es la contraseña asignada para usted"]);
            }

            $data = $data[0];
            return view('Alumn.account.steps')->with(["step"=>2,"alumn"=>$data]);
        }
        else if ($step==2)
        {
            //validamos que no haya ninguna sesion abierta, si la hay la cerramos
            if (Auth::guard("alumn")->check())
            {
                Auth::guard('alumn')->logout();
                session()->flush();
            }

            //traemos la data de sicoes y validamos que no venga vacio
            $data = selectSicoes("Alumno","Matricula",$request->input('matricula'));
            if (count($data)==0)
            {
                session()->flash("messages","error|No pudimos completar el registro, problemas con sicoes");;
                return redirect()->back();
            }
            $data = $data[0];

            //intentar guardar los cambios
            try
            {
                $user = new User();
                $user->name = $data["Nombre"];
                $user->lastname = $data["ApellidoPrimero"]." ".$data["ApellidoSegundo"];
                $user->email = $request->input('email');
                $user->password = bcrypt($request->input("password"));
                $user->id_alumno = $data["AlumnoId"];
                $user->save();

                $validate = Pending::where("enrollment","=",$request->input('matricula'))->first();
                $validate->status=1;
                $validate->save();


                //intentamos acceder al sistema con el nuevo usuario creado, si no se puede acceder borramos el registro y lo reenviamos hacerlo de nuevo
                $credentials = $request->only('email', 'password');
                if (Auth::guard('alumn')->attempt($credentials))
                {
                    return redirect()->route('alumn.home');
                }
                else
                {
                    session()->flash("messages","info|No pudimos iniciar sesion, intenta hacerlo con tus credenciales");
                    return redirect()->route("alumn.home");
                }
            }
            catch(\Exception $e)
            {
                session()->flash("messages","error|No pudimos completar el registro, errores internos");
                return redirect()->route('alumn.users.first_step'); 
            }              
        }
    }

    public function registerAlumn(Request $request) 
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
            'name'=>'required',
            'lastname'=>'required'
        ]);

        //cerrar cualquier session que haya abierta.
        if (Auth::guard("alumn")->check())
        {
            Auth::guard('alumn')->logout();
            session()->flush();
        }
        
        //validar que un correo no exista.
        $validate = User::where("email","=", $request->input("email"))->get();

        if(!$validate->isEmpty())
        {
            session()->flash("messages","error|El correo ".$request->input("email")." ya esta registrado");
            return redirect()->back()->withInput();
        }

        //intentar registrar al alumno, cualquier error que surja se envia de nuevo al registro
        try
        {
            $user = new User();
            $user->name = strtoupper($request->input("name"));
            $user->lastname = strtoupper($request->input("lastname"));
            $user->email = $request->input("email");
            $user->password = bcrypt($request->input("password"));
            $user->save(); 
        }
        catch(\Exception $e)
        {
           session()->flash("messages","error|Surgio un error intentanto registrarlo");
           return redirect()->back(); 
        }

        $credentials = $request->only('email', 'password');

        if (Auth::guard('alumn')->attempt($credentials))
        {
            return redirect()->route('alumn.home');
        }
        else
        {
            return redirect()->route("alumn.login");
        }
    }
}