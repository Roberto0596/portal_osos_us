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
                $user = User::where("id_alumno", $value->id_alumno)->first();
                if (!$value->enrollment || !$value->alumn_name || !$value->alumn_last_name || !$value->alumn_second_last_name) {
                    $value->enrollment = $user->sAlumn->Matricula;
                    $value->alumn_name = $user->sAlumn->Nombre;
                    $value->alumn_last_name = $user->sAlumn->ApellidoPrimero;
                    $value->alumn_second_last_name = (isset($user->sAlumn->ApellidoSegundo) ? $user->sAlumn->ApellidoSegundo : '');
                    $value->save();
                }                
            } catch(\Exception $e) {
            }
        }
    }
}
