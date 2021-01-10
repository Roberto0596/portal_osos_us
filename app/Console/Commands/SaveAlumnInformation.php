<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\updateAlumn;

class SaveAlumnInformation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alumn:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza los campos de alumno';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        dispatch(new updateAlumn());
    }
}
