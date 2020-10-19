<?php

use Illuminate\Database\Seeder;
use App\Models\Alumns\DebitType;

class DebitSeeder extends Seeder
{
    public function run()
    {
        $array = [
            ["concept" => "Inscripción"], 
            ["concept" => "Danio Material"], 
            ["concept" => "Retrasos"], 
            ["concept" => "Robo"], 
            ["concept" => "Documento oficial"]
        ];

        foreach ($array as $key => $value) {
            $select = DebitType::where("concept", "=", $value["concept"])->first();
            if (!$select) {
                $select = new DebitType();
            }
            $select->concept = $value["concept"];
            $select->save();
        }

        $out = new \Symfony\Component\Console\Output\ConsoleOutput();
        $out->writeln("<info>conceptos de pago creados created</info>");
    }
}