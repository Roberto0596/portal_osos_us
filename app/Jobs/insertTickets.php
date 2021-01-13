<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Alumns\Debit;
use App\Models\Alumns\Ticket;


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
        $debits = Debit::where("period_id", getConfig()->period_id)->get();
        foreach ($debits as $key => $value) {
            try {

                $ticket = Ticket::where("debit_id", $value->id)->first();

                if (!$ticket) {
                    createTicket($value->id);
                } else {

                    if ($value->status == 0) {
                       $ticket->delete();
                    } 
                }          
            } catch(\Exception $e) {
                $out = new \Symfony\Component\Console\Output\ConsoleOutput();
                $out->writeln("<warning>An error has occurred</warning>");
            }
        }
        $out = new \Symfony\Component\Console\Output\ConsoleOutput();
        $out->writeln("<info>Tickets inserted successfully</info>");
    }
}
