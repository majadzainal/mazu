<?php

namespace App\Mail;

use App\Models\MazuMaster\BroadcastEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BroadcastEmailSend extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $bc;
    public function __construct(BroadcastEmail $bc)
    {
        $this->bc = $bc;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
                ->subject($this->bc->subject)
                ->replyTo("promosi@mazurejeki88-system.com", "MAZU LABEL")
                ->view('mails.broadcastEmail');
    }
}
