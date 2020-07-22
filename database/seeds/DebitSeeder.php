<?php

use Illuminate\Database\Seeder;

class DebitSeeder extends Seeder
{
    public function run()
    {
        if (! DB::table('debit_type')->count() ) 
        {
            DB::table('debit_type')->insert([
                'concept' => 'Inscripción',
            ]);
            DB::table('debit_type')->insert([
                'concept' => 'Danio Material',
            ]);
            DB::table('debit_type')->insert([
                'concept' => 'Retrasos',
            ]);
            DB::table('debit_type')->insert([
                'concept' => 'Robo',
            ]);
            $out = new \Symfony\Component\Console\Output\ConsoleOutput();
            $out->writeln("<info>conceptos de pago creados created</info>");
        }
        else
        {
        	$out = new \Symfony\Component\Console\Output\ConsoleOutput();
            $out->writeln("<warning>nothing to Seeder</warning>");
        }
    }
}