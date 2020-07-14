<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Website\Pending;

class PendingsController extends Controller
{
    public function loadData(){

        $alumns = selectSicoes("Alumno");

       

        foreach($alumns as $alumn){

            $pendig = new Pending();
            $pass = $this->generatePasssword();
            $pendig->enrollment = $alumn['Matricula'];
            $pendig->password   = $pass;
            $pendig->save();
           
        }


        session()->flash('messages', 'success|Datos cargados correctamente');
        return redirect()->back();
       
    }

    private function generatePasssword(){

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

    public function generatePdf()
    {
        return view('Website\homepdf');
    }
    
}
