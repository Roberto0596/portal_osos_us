<?php

namespace App\Http\Controllers\Alumn;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Alumns\User;
use Illuminate\Support\Collection;
use App\Http\Requests\CreateUserRequest;
use App\Models\Website\Pending;
use App\Models\Sicoes\Alumno;
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
        $enrollment = $request->input('matricula');
        $data = Alumno::where("Matricula", $enrollment)->first();

        if ($data) {
            if ($step == 1) {
                
                $password = $request->input('password');
                $validate = Pending::where("enrollment", $enrollment)->where("status",0)->first();

                if (!$validate) {
                    return view('Alumn.account.steps')->with([
                        "step"=>1,
                        "error"=>"No hay un registro pendiente con la matricula: ".$enrollment
                    ]);
                }

                if($validate->password != $password)
                {
                    return view('Alumn.account.steps')->with([
                        "step" => 1,
                        "error" => "Contraseña incorrecta."
                    ]);
                }

                return view('Alumn.account.steps')->with([
                    "step"=>2,
                    "alumn" => $data
                ]);
            }
            else if ($step==2)
            {
                //validamos que no haya ninguna sesion abierta, si la hay la cerramos
                closeAllSessions("alumn");

                try {

                    $user = new User();
                    $user->name = normalizeChars($data->Nombre);
                    $user->lastname = normalizeChars($data->ApellidoPrimero." ".$data->ApellidoSegundo);
                    $user->email = $request->input('email');
                    $user->password = bcrypt($request->input("password"));
                    $user->id_alumno = $data->AlumnoId;
                    $user->save();

                    $validate = Pending::where("enrollment","=",$request->input('matricula'))->first();
                    $validate->status=1;
                    $validate->save();

                    $credentials = $request->only('email', 'password');
                    if (Auth::guard('alumn')->attempt($credentials)) {
                        session()->flash("messages", "success|Bienvenido".$user->name.".");
                        return redirect()->route('alumn.home');
                    } else {
                        session()->flash("messages","info|No pudimos iniciar sesion, intenta hacerlo con tus credenciales");
                        return redirect()->route("alumn.home");
                    }
                } catch(\Exception $e) {
                    session()->flash("messages","error|Ocurrio un problema al intentar guardar");
                    return redirect()->route('alumn.users.first_step'); 
                }              
            }
        } else {
            session()->flash("messages", "error|No encontramos tu registro");
            return redirect()->route("alumn.home");
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

        closeAllSessions("alumn");
        
        //validar que un correo no exista.
        $validate = User::where("email","=", $request->input("email"))->first();

        if($validate) {
            session()->flash("messages","error|El correo ".$request->input("email")." ya esta registrado");
            return redirect()->back()->withInput();
        }

        //intentar registrar al alumno, cualquier error que surja se envia de nuevo al registro
        try {
            $user = new User();
            $user->name = normalizeChars($request->input("name"));
            $user->lastname = normalizeChars($request->input("lastname"));
            $user->email = $request->input("email");
            $user->password = bcrypt($request->input("password"));
            $user->save(); 
            session()->flash("messages", 'success|Su registro se realizó con éxito|De click en el botón Acceso Nuevo Ingreso (Color Naranja ) para iniciar sesión');
            return redirect()->back(); 
        } catch(\Exception $e) {
           session()->flash("messages","error|Opps, ocurrió un problema que no esperabamos.");
           return redirect()->back(); 
        }

        //$credentials = $request->only('email', 'password');

        /*if (Auth::guard('alumn')->attempt($credentials)) {
            session()->flash("messages", "success|Bienvenido ".ucwords($user->name).".");
            return redirect()->route('alumn.home');
        } else {
            return redirect()->route("alumn.login");
        }*/

        
    }
}