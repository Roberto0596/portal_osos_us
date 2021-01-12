<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\addPending;

class addPendingTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pending:add {enrollment}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert new pending';

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
        dispatch(new addPending($this->argument('enrollment')));
    }
}
