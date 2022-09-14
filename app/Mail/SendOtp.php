<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOtp extends Mailable
{
    use Queueable, SerializesModels;
    public $email, $otp;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email = '', $otp = '')
    {
        //
        $this->email = $email;
        $this->otp = $otp;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "Your One Time Password For Translation";
        // \Log::info('path => ' . $this->fileTranslated);
        return $this->view('emails.send-otp-email')
            // ->bcc(["jemish@logisticinfotech.co.in"])
            ->subject($subject)
            ->with([
                'email'=> $this->email,
                'otp'=>$this->otp,
            ]);

        return $this->view('view.name');
    }
}
