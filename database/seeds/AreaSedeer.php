<?php

use Illuminate\Database\Seeder;

class AreaSedeer extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (! DB::table('area')->count() ) {
            DB::table('area')->insert([
                'name' => 'Centro de cómputo',
            ]);
            DB::table('area')->insert([
                'name' => 'Finanzas',
            ]);
            DB::table('area')->insert([
                'name' => 'Biblioteca',
            ]);
            $out = new \Symfony\Component\Console\Output\ConsoleOutput();
            $out->writeln("<info>areas created</info>");
        }
        else
        {
        	$out = new \Symfony\Component\Console\Output\ConsoleOutput();
            $out->writeln("<warning>nothing to Seeder</warning>");
        }
    }
}
