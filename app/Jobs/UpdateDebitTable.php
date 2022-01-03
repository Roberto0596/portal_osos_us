<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Alumns\Debit;
use App\Models\Alumns\User;

class UpdateDebitTable implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $debits = Debit::all();
        foreach ($debits as $key => $value) {
            try {
                $user = $value->Alumn;
                if(isset($user) && $user) {

                    if (!$value->enrollment || !$value->alumn_name || !$value->alumn_last_name || !$value->alumn_second_last_name) {        
                        $value->enrollment = $user->Matricula;
                        $value->alumn_name = $user->Nombre;
                        $value->alumn_last_name = $user->ApellidoPrimero;
                        $value->alumn_second_last_name = (isset($user->ApellidoSegundo) ? $user->ApellidoSegundo : '');
                        echo "Saving alumn general data";
                    }  

                    if (!$value->career || !$value->location) {
                        $value->location = $user->Localidad;
                        $value->state = $user->Estado->Nombre;
                        $value->career = $user->PlanEstudio->Carrera->Nombre;
                        echo "Saving alumn academic data";
                    }

                    if(!$value->phone || !$value->email){
                        $value->phone = $user->Telefono;
                        $value->email = User::where('id_alumno', $value->id_alumno)->first()->email;
                        echo "Saving alumn contact data";

                    }

                    $value->save();
                }             
            } catch(\Exception $e) {
                echo "problem with: " . $value->id;
            }
        }
    }
}
