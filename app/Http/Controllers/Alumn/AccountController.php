<?php

namespace App\Http\Controllers\Alumn;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Alumns\User;
use App\Models\Website\Pending;
use App\Models\Sicoes\Alumno;
use App\Library\Log;
use Auth;

class AccountController extends Controller
{
    private $logger;

    public function callAction($method, $parameters)
    {
        $this->logger = new Log(AccountController::class);
        return parent::callAction($method, $parameters);
    }

	public function index() 
	{
        $this->logger->info("###Iniciando proceso de alta de alumno re-inscripcion paso 1");
        $step = 1;
        return view('Alumn.account.steps')->with(["step"=>$step]);
    }

    public function save(Request $request, $step)
    {
        $this->logger->info("entrando a paso: " . $step);
        $enrollment = $request->input('matricula');
        $this->logger->info("matricula a buscar: " . $enrollment);
        $data = Alumno::where("Matricula", $enrollment)->first();

        if ($data) {

            if ($step == 1) {
                $this->logger->info("Continua paso 1 se procede a validar registro en tabla pendings");
                $password = $request->input('password');
                $validate = Pending::where("enrollment", $enrollment)->where("status", 0)->first();

                if (!$validate) {
                    $this->logger->info("No se encontró registro con matricula: " . $enrollment);
                    return view('Alumn.account.steps')->with([
                        "step"=>1,
                        "error"=>"No hay un registro pendiente con la matricula: ".$enrollment
                    ]);
                }

                if($validate->password != $password) {
                    return view('Alumn.account.steps')->with([
                        "step" => 1,
                        "error" => "Contraseña incorrecta."
                    ]);
                }

                return view('Alumn.account.steps')->with([
                    "step"=>2,
                    "alumn" => $data
                ]);
            } else if ($step==2) {
                $this->logger->info("Segimos en paso 2, se procede a validar que no hay sesiones abiertas");
                //validamos que no haya ninguna sesion abierta, si la hay la cerramos
                closeAllSessions("alumn");

                try {
                    $this->logger->info("Se procede a insertar al alumno en portal");
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
                    } else {
                        session()->flash("messages","info|No pudimos iniciar sesion, intenta hacerlo con tus credenciales");                       
                    }
                    $this->logger->info("Paso dos ejecutado con exito");
                    $this->logger->info("##proceso de alta de alumno terminado");
                    return redirect()->route("alumn.home");
                } catch(\Exception $e) {
                    $this->logger->info("Ocurrió un error del tipo: " . $e->getMessage());
                    session()->flash("messages","error|Ocurrio un problema al intentar guardar");
                    return redirect()->route('alumn.users.first_step'); 
                }              
            }
        } else {
            $this->logger->info("No existe registro en sicoes con matricula " . $enrollment);
            session()->flash("messages", "error|No encontramos tu registro");
            return redirect()->route("alumn.home");
        }
    
    }

    public function registerAlumn(Request $request) 
    {
        $this->logger->info("##Iniciando proceso de registro de alumnos de nuevo ingreso");

        $request->validate([
            'email' => 'required',
            'password' => 'required',
            'name'=>'required',
            'lastname'=>'required'
        ]);

        $this->logger->info("Se procede a cerrar toda sesion abierta");
        closeAllSessions("alumn");
        
        $this->logger->info("Se valida que no exista correo en portal");
        //validar que un correo no exista.
        $validate = User::where("email","=", $request->input("email"))->first();

        if($validate) {
            $this->logger->info("El correo " . $validate->email . " ya se encuentra registrado");
            session()->flash("messages","error|El correo ".$request->input("email")." ya esta registrado");
            return redirect()->back()->withInput();
        }

        //intentar registrar al alumno, cualquier error que surja se envia de nuevo al registro
        try {
            $this->logger->info("Se procede a insertar al alumno en portal");
            $user = new User();
            $user->name = normalizeChars($request->input("name"));
            $user->lastname = normalizeChars($request->input("lastname"));
            $user->email = $request->input("email");
            $user->password = bcrypt($request->input("password"));
            $user->save(); 
            $this->logger->info("Proceso de alta de alumno de nuevo ingreso realizado con exito");
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