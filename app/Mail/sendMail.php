<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class sendMail extends Mailable
{
    use Queueable, SerializesModels;

    public $emailVal;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($val)
    {
        $this->emailVal = $val;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('info@lhh.co.id', $this->emailVal->subject)
                    ->view('mails.poEmail')
                    ->attach(public_path('assets/tempFile/').$this->emailVal->attach, [
                      'as' => $this->emailVal->attach,
                      'mime' => 'application/pdf',
                    ]);
    }
}
