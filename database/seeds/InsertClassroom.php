<?php

use Illuminate\Database\Seeder;
use App\Models\Logs\ClassRoom;

class InsertClassroom extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = [
            ["name" => "Sala 1", "area_id" => 1, "code" => "CC1", "num" => 1],
            ["name" => "Sala 2", "area_id" => 1, "code" => "CC2", "num" => 2],
        ];


        foreach ($array as $value) {
        	$select = ClassRoom::where("code", $value["code"])->first();

        	if (!$select) {
        		$select = new ClassRoom();
        	}
            
            foreach ($value as $key => $item) {
            	$select->$key = $item;
            }            
            $select->save();
        }

        $out = new \Symfony\Component\Console\Output\ConsoleOutput();
        $out->writeln("<info>Aulas creadas</info>");
    }
}
