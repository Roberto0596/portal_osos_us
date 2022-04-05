<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Alumns\User;
use App\Models\Alumns\PasswordRequest;
use App\Library\Log;
use Input;

class WebsiteController extends Controller
{
    private $logger;

    public function callAction($method, $parameters)
    {
        $this->logger = new Log(HomeController::class);
        return parent::callAction($method, $parameters);
    }

	public function index()
	{
        $this->logger->info("Hola mudno");
		return view('Website.register');
	}

    public function inMaintenance()
	{
		return view('Website.maintenance');
	}

    public function viewRestore($token) {
        $instance = PasswordRequest::where("token", $token)->first();
        if (!$instance) {
            return redirect()->route("home");
        }
        return view('Alumn.auth.view-restore',["instance" => $instance]);
    }

    public function restorePassword(Request $request, PasswordRequest $instance) {
        $instance->alumn->password = bcrypt($request->get('password'));
        $instance->alumn->save();
        PasswordRequest::destroy($instance->id);
        session()->flash("messages","success|Contreña actualizada");
        return redirect()->route("alumn.login");
    }

    public function delete($id)
    {
    }

    public function save(Request $request, Categories $categorie) 
    {
    }
}