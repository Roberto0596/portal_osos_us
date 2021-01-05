<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AreaSedeer::class);
        $this->call(AdminSeeder::class);
        $this->call(DebitSeeder::class);
        $this->call(period::class);
        $this->call(ConfigSeeder::class);
        $this->call(document_type::class);
        $this->call(promediosAltosSeed::class);
    }
}
