<?php

use Illuminate\Database\Seeder;

class period extends Seeder
{
    public function run()
    {
        if (! DB::table('period')->count())
        {
            DB::table('period')->insert([
                'id' => '4026',
                'clave' => '2020-01',
                'año' => '2020',
                'ciclo' => '2019-2020',
                'semestre' => '1',
                'created_at' => now()
            ]);

            $out = new \Symfony\Component\Console\Output\ConsoleOutput();
            $out->writeln("<info>period created</info>");
        }
        else
        {
            $out = new \Symfony\Component\Console\Output\ConsoleOutput();
            $out->writeln("<warning>nothing to created</warning>");
        }
    }
}
