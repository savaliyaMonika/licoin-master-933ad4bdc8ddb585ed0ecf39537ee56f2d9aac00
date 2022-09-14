<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ErrorNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Error html content
     */

    public $htmlError;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($htmlError)
    {
        $this->htmlError = $htmlError;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.errornotification-email')
            ->with('content', $this->htmlError)
            ->subject("Licoin Error Report");
    }
}
