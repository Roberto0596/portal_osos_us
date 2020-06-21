<?php

namespace App\Http\Controllers\Alumn;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Alumns\User;
use Input;
use Auth;
use Illuminate\Support\Collection;

class UserController extends Controller
{
	public function index()
	{
        $current_id = Auth::guard('alumn')->user()->id;
        $current_user = User::find($current_id);
		return view('Alumn.user.index')->with(["user"=>$current_user]);
	}

    public function save(Request $request, User $user)
    {
        if ($request->input('password')!=null)
        {
            $user->password = bcrypt($request->input('password'));
        }

        if(isset($_FILES['newPicture']))
        {
            if ($user->photo == "img/alumn/default/default.png")
            {
                $routePicture = ctrCrearImagen($_FILES["newPicture"],$user->id,"alumn",100,120,false);
                $user->photo = $routePicture;
            }
            else
            {
                unlink($user->photo);

                $routePicture = ctrCrearImagen($_FILES["newPicture"],$user->id,"alumn",100,120,true);
                $user->photo = $routePicture;
            }
        }

        $user->name = $request->input("name");
        $user->lastname = $request->input("lastname");
        $user->save();
        session()->flash("messages","success|Datos guardados correctamente");
        return redirect()->route("alumn.user");
    }

	public function steps() 
	{
        $step = 1;
        return view('Alumn.account.steps')->with(["step"=>$step]);
    }

    public function postSteps(Request $request,$step)
    {
        //primer paso, buscar en la base de datos sicoes y validar que la matricula existe para posteriormente enviar al paso dos
        if ($step == 1)
        {
            $matricula = $request->input('matricula');
            $data = selectSicoes("alumno","matricula",$matricula);
            if (count($data)==0)
            {
                return view('Alumn.account.steps')->with(["step"=>1,"error"=>"Esta matricula no existe, si no la recuerda favor de comunicarse a servicios escolares"]);
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
            
            //paso dos, primero validamos que no este registrado el correo en la base de datos, para ello hacemos una consulta y validamos que este vacia.
            $email = $request->input("email");
            $validate = User::where("email","=",$email)->get();

            if (!$validate->isEmpty()) 
            {
                session()->flash("messages","info|Ya estas registrado, intenta ingresar");
                return redirect()->route("alumn.home");
            }

            //creamos el nuevo usuario que cuardaremos en la base de datos.
            $user = new User();

            $password = $request->input("password");
            $matricula = $request->input('matricula');
            $data = selectSicoes("alumno","matricula",$matricula)[0];
            
            $user->name = $data["nombre"];
            $user->lastname = $data["apellidoprimero"]." ".$data["apellidosegundo"];
            $user->email = $email;
            $user->password = bcrypt($password);
            $user->id_alumno = $data["alumnoid"];
            $user->save();

            $credentials = $request->only('email', 'password');

            //intentamos acceder al sistema con el nuevo usuario creado, si no se puede acceder borramos el registro y lo reenviamos hacerlo de nuevo
            if (Auth::guard('alumn')->attempt($credentials))
            {
                return redirect()->route('alumn.home');
            }
            else
            {
                User::destroy($user->id);
                session()->flash("message","error|No se completo el registro, favor de intentarlo de nuevo");
                return redirect()->route("alumn.users.first_step");
            } 
        }
    }

    public function delete($id)
    {
    }

    public function registerAlumn(Request $request, User $user) 
    {
        //validar que un correo no exista.
        $validate = User::where("email","=", $request->input("email"))->get();

        if(!$validate->isEmpty())
        {
            return redirect()->route("home");
        }

        $user->name = $request->input("name");
        $user->lastname = $request->input("lastname");
        $user->email = $request->input("email");
        $user->password = bcrypt($request->input("password"));
        $user->save();

        if (Auth::guard("alumn")->check())
        {
            Auth::guard('alumn')->logout();
            session()->flush();
        } 

         $credentials = $request->only('email', 'password');

        if (Auth::guard('alumn')->attempt($credentials))
        {
            return redirect()->route('alumn.home');
        }
        else
        {
            User::destroy($user->id);
            session()->flash("message","error|No se completo el registro, favor de intentarlo de nuevo");
            return redirect()->route("home");
        }
    }
}