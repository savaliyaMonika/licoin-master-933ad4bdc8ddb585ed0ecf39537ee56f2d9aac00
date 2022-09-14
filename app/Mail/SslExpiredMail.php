<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SslExpiredMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mailData;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mailData)
    {
        $this->mailData = $mailData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $webSite = $this->mailData['web_site'];
        $subject = "Your SSL certificate is expiring in a week - " . $webSite;

        return $this->view('emails.sslexpired-email')
            // ->bcc(["chintan@logisticinfotech.com", "alpesh@logisticinfotech.com"])
            // ->from("www.logisticinfotech.com", "SSL-CHECKER")
            ->with([
                'certificate'=> $this->mailData
            ])
            ->subject($subject);
    }
}
