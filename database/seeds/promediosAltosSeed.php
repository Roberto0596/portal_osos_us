<?php

use Illuminate\Database\Seeder;
use App\Models\Alumns\HighAverages;

class promediosAltosSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = [
            ["enrollment" => "20-05-0010", "periodo_id" => "4029", "status" => 0],
            ["enrollment" => "19-05-0023", "periodo_id" => "4029", "status" => 0],
            ["enrollment" => "18-05-0021", "periodo_id" => "4029", "status" => 0],
            ["enrollment" => "20-03-0033", "periodo_id" => "4029", "status" => 0],
            ["enrollment" => "19-03-0004", "periodo_id" => "4029", "status" => 0],
            ["enrollment" => "18-03-0020", "periodo_id" => "4029", "status" => 0],
            ["enrollment" => "18-03-0008", "periodo_id" => "4029", "status" => 0],
            ["enrollment" => "17-03-0059", "periodo_id" => "4029", "status" => 0],
            ["enrollment" => "20-01-0002", "periodo_id" => "4029", "status" => 0],
            ["enrollment" => "19-01-0038", "periodo_id" => "4029", "status" => 0],
            ["enrollment" => "17-01-0028", "periodo_id" => "4029", "status" => 0],
            ["enrollment" => "19-06-0003", "periodo_id" => "4029", "status" => 0],
            ["enrollment" => "20-02-0039", "periodo_id" => "4029", "status" => 0],
            ["enrollment" => "19-02-0054", "periodo_id" => "4029", "status" => 0],
            ["enrollment" => "18-02-0003", "periodo_id" => "4029", "status" => 0],
            ["enrollment" => "17-02-0007", "periodo_id" => "4029", "status" => 0],
        ];

        foreach ($array as $value) {
            $select = new HighAverages();
            foreach ($value as $key => $item) {
            	$select->$key = $item;
            }            
            $select->save();
        }

        $out = new \Symfony\Component\Console\Output\ConsoleOutput();
        $out->writeln("<info>Alumnos excentos creados</info>");
    }
}
