<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Alumns\Debit;
use App\Models\Alumns\Ticket;
use App\Library\Ticket as TicketLibrary;
use App\Enum\DebitStatus;


class insertTickets implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        set_time_limit(400);
        //$debits = Debit::where("period_id", getConfig()->period_id)->get();
        $debits = Debit::all();
        foreach ($debits as $key => $value) {
            try {

                $ticket = Ticket::where("debit_id", $value->id)->first();

                if (!$ticket) {
                    if ($value->status == Debit::getStatus(DebitStatus::paid())) {
                        TicketLibrary::build($value);
                    }
                } else {
                    if ($value->status == 0) {
                       $ticket->delete();
                    }
                }          
            } catch(\Exception $e) {
                $out = new \Symfony\Component\Console\Output\ConsoleOutput();
                $out->writeln("<warning>An error has occurred: ".$e->getMessage()."</warning>");
            }
        }
        $out = new \Symfony\Component\Console\Output\ConsoleOutput();
        $out->writeln("<info>Tickets inserted successfully</info>");
    }
}