<?php

namespace App\Http\Controllers\FinancePanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function changeSerie(Request $request){


        try{

            $config = getConfig();
            $config->ticket_serie = $request->serie;
            $config->debit_ticket_count = 0;
            $config->save();
            session()->flash("messages","success|Serie actualizada");
            return redirect()->back();

        }catch(\Exception $e){
            session()->flash("messages","success|Algo salió mal");
            return redirect()->back();
        }
            
       
    }
}
