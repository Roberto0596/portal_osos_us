<?php namespace App\Broadcast;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NotifyFinance implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $target;

    public function __construct($target, $message)
    {
        $this->message = $message;
        $this->target = $target;
    }

    public function broadcastOn()
    {
        return ['finance-channel'];
    }

    public function broadcastAs()
    {
        return 'finance-event';
    }
}