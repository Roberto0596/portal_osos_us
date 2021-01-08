<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\UpdateDebitTable;

class SaveDebitInformation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debit:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update debit info';

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
        dispatch(new UpdateDebitTable());
    }
}
