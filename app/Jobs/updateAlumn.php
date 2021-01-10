<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Alumns\User;

class updateAlumn implements ShouldQueue
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
        $users = User::all();
        foreach ($users as $key => $value) {
            try {
                if (!$value->enrollment) {
                    $alumnData = $value->getSicoesData();
                    $value->enrollment = $alumnData["Matricula"];
                    $value->save();
                }                
            } catch(\Exception $e) {
            }
        }
    }
}
