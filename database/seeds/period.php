<?php

use Illuminate\Database\Seeder;

class period extends Seeder
{
    public function run()
    {
        if (! DB::table('period')->count())
        {
            DB::table('period')->insert([
                'id' => '4027',
                'clave' => '2020-02',
                'año' => '2020',
                'ciclo' => '2020-2021',
                'semestre' => '2',
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
