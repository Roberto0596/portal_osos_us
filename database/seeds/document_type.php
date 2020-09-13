<?php

use Illuminate\Database\Seeder;

class document_type extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (! DB::table('document_type')->count())
        {
            DB::table('document_type')->insert([
                'name' => 'Acta de Nacimiento',
                'created_at' => now()
            ]);

            DB::table('document_type')->insert([
                'name' => 'Kardex o Constancia',
                'created_at' => now()
            ]);

            DB::table('document_type')->insert([
                'name' => 'CURP',
                'created_at' => now()
            ]);

            DB::table('document_type')->insert([
                'name' => 'IMSS',
                'created_at' => now()
            ]);

            DB::table('document_type')->insert([
                'name' => 'Fotografia',
                'created_at' => now()
            ]);

            DB::table('document_type')->insert([
                'name' => 'Constancia de no adeudo',
                'created_at' => now()
            ]);

            DB::table('document_type')->insert([
                'name' => 'Cedula',
                'created_at' => now()
            ]);

            $out = new \Symfony\Component\Console\Output\ConsoleOutput();
            $out->writeln("<info>documents types created</info>");
        }
        else
        {
            $out = new \Symfony\Component\Console\Output\ConsoleOutput();
            $out->writeln("<warning>nothing to created</warning>");
        }
    }
}
