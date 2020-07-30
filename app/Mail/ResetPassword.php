<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;
    public $subject;
    public $data;

    public function __construct($subject,$data)
    {
        $this->subject = $subject;
        $this->data = $data;
    }

    public function build()
    {
        return $this->view('AdminPanel.RequestPass.email-template');
    }
}
