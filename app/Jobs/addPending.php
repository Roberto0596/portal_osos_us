<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Website\Pending;

class addPending implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $enrollment;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($enrollment)
    {
        $this->enrollment = $enrollment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $out = new \Symfony\Component\Console\Output\ConsoleOutput();
        try {
            if (strpos($this->enrollment, "-") !== false) {
                $pending = Pending::where("enrollment", $this->enrollment)->first();

                if (!$pending) {
                    $pending = new Pending();
                    $pending->enrollment = $this->enrollment;
                    $pending->password = $this->generatePasssword();
                    $pending->PlanEstudioId = 0;
                    $pending->EncGrupoId = 0;
                }

                $pending->status = 0;
                $pending->save();
                $out->writeln("<info>Register inserted successfully</info>");
                $out->writeln("<info>password: ".$pending->password."</info>");
            } else {
                $out->writeln("<warning>Incorrect format: '-' expected in enrollment</warning>");
            }
        } catch(\Exception $e) {
            $out->writeln("<warning>An error has occurred: ".$e->getMessage()."</warning>");
        }
    }

    private function generatePasssword()
    {
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
}
