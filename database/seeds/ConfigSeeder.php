<?php

use Illuminate\Database\Seeder;

class ConfigSeeder extends Seeder
{
    public function run()
    {
        $period = selectCurrentPeriod();
        if (! DB::table('config')->count())
        {
            DB::table('config')->insert([
                'open_inscription' => 1,
                'period_id' => $period->id,
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
