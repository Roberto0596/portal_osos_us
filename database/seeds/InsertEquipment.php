<?php

use Illuminate\Database\Seeder;
use App\Models\Logs\Equipment;

class InsertEquipment extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	//code mix area, equipo, aula y num
        $array = [
            ["classroom_id" => 1, "code" => "CCE1", "num" => 1],
            ["classroom_id" => 1, "code" => "CCE2", "num" => 2],
            ["classroom_id" => 1, "code" => "CCE3", "num" => 3],
            ["classroom_id" => 1, "code" => "CCE4", "num" => 4],
            ["classroom_id" => 1, "code" => "CCE5", "num" => 5],
            ["classroom_id" => 1, "code" => "CCE6", "num" => 6],
            ["classroom_id" => 1, "code" => "CCE7", "num" => 7],
            ["classroom_id" => 1, "code" => "CCE8", "num" => 8],
            ["classroom_id" => 1, "code" => "CCE9", "num" => 9],
            ["classroom_id" => 1, "code" => "CCE10", "num" => 10],
            ["classroom_id" => 1, "code" => "CCE11", "num" => 11],
            ["classroom_id" => 1, "code" => "CCE12", "num" => 12],
            ["classroom_id" => 1, "code" => "CCE13", "num" => 13],
            ["classroom_id" => 1, "code" => "CCE14", "num" => 14],
            ["classroom_id" => 1, "code" => "CCE15", "num" => 15],
            ["classroom_id" => 1, "code" => "CCE16", "num" => 16],
            ["classroom_id" => 1, "code" => "CCE17", "num" => 17],
            ["classroom_id" => 1, "code" => "CCE18", "num" => 18],
            ["classroom_id" => 1, "code" => "CCE19", "num" => 19],
            ["classroom_id" => 1, "code" => "CCE20", "num" => 20],
            ["classroom_id" => 1, "code" => "CCE21", "num" => 21],
            ["classroom_id" => 1, "code" => "CCE22", "num" => 22],
            ["classroom_id" => 1, "code" => "CCE23", "num" => 23],
            ["classroom_id" => 1, "code" => "CCE24", "num" => 24],
            ["classroom_id" => 1, "code" => "CCE25", "num" => 25],

            ["classroom_id" => 2, "code" => "CCE26", "num" => 26],
            ["classroom_id" => 2, "code" => "CCE27", "num" => 27],
            ["classroom_id" => 2, "code" => "CCE28", "num" => 28],
            ["classroom_id" => 2, "code" => "CCE29", "num" => 29],
            ["classroom_id" => 2, "code" => "CCE30", "num" => 30],
            ["classroom_id" => 2, "code" => "CCE31", "num" => 31],
            ["classroom_id" => 2, "code" => "CCE32", "num" => 32],
            ["classroom_id" => 2, "code" => "CCE33", "num" => 33],
            ["classroom_id" => 2, "code" => "CCE34", "num" => 34],
            ["classroom_id" => 2, "code" => "CCE35", "num" => 35],
            ["classroom_id" => 2, "code" => "CCE36", "num" => 36],
            ["classroom_id" => 2, "code" => "CCE37", "num" => 37],
            ["classroom_id" => 2, "code" => "CCE38", "num" => 38],
            ["classroom_id" => 2, "code" => "CCE39", "num" => 39],
            ["classroom_id" => 2, "code" => "CCE40", "num" => 40],
            ["classroom_id" => 2, "code" => "CCE41", "num" => 41],
            ["classroom_id" => 2, "code" => "CCE42", "num" => 42],
            ["classroom_id" => 2, "code" => "CCE43", "num" => 43],
            ["classroom_id" => 2, "code" => "CCE44", "num" => 44],
            ["classroom_id" => 2, "code" => "CCE45", "num" => 45],
            ["classroom_id" => 2, "code" => "CCE46", "num" => 46],
            ["classroom_id" => 2, "code" => "CCE47", "num" => 47],
            ["classroom_id" => 2, "code" => "CCE48", "num" => 48],
            ["classroom_id" => 2, "code" => "CCE49", "num" => 49],
            ["classroom_id" => 2, "code" => "CCE59", "num" => 59],
        ];


        foreach ($array as $value) {
        	$select = Equipment::where("code", $value["code"])->first();

        	if (!$select) {
        		$select = new Equipment();
        	}
            
            foreach ($value as $key => $item) {
            	$select->$key = $item;
            }            
            $select->save();
        }

        $out = new \Symfony\Component\Console\Output\ConsoleOutput();
        $out->writeln("<info>equipos cread0s</info>");
    }
}
