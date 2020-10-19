<?php

use Illuminate\Database\Seeder;
use App\Models\Alumns\DocumentType;
class document_type extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = [
            ["name" => "Acta de Nacimiento", "type" => 0, "cost" => null], 
            ["name" => "Kardex o Constancia", "type" => 0, "cost" => null], 
            ["name" => "CURP", "type" => 0, "cost" => null], 
            ["name" => "IMSS", "type" => 0, "cost" => null], 
            ["name" => "Fotografia", "type" => 0, "cost" => null],
            ["name" => "Constancia de no adeudo", "type" => 0, "cost" => null],
            ["name" => "Cedula", "type" => 0, "cost" => null],
            ["name" => "Contancia de estudio", "type" => 1, "cost" => 35],
            ["name" => "Kardex", "type" => 1, "cost" => 200]
        ];

        foreach ($array as $key => $value) {
            $select = DocumentType::where("name", "=", $value["name"])->first();
            if (!$select) {
                $select = new DocumentType();
            }
            $select->name = $value["name"];
            $select->type = $value["type"];
            $select->cost = $value["cost"];
            $select->save();
        }

        $out = new \Symfony\Component\Console\Output\ConsoleOutput();
        $out->writeln("<info>documents types created</info>");
    }
}
