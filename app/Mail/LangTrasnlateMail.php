<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LangTrasnlateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $fileTranslated, $extraText, $mailType;
    /**
     * Create a new message instance.
     *
     * @return void
     */

    public function __construct($fileTranslated = "", $extraText = "", $mailType="")
    {
        $this->fileTranslated = $fileTranslated;
        $this->extraText = $extraText;
        $this->mailType = $mailType;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "Translated Language File";
        if($this->extraText) {
            $subject = "Error Translating Language File";
        }
        // \Log::info('path => ' . $this->fileTranslated);
        return $this->view('emails.langtranslate-email')
            // ->bcc(["chintan@logisticinfotech.com", "alpesh@logisticinfotech.com"])
            ->bcc(["jemish@logisticinfotech.co.in"])
            ->subject($subject)
            // ->attach($this->fileTranslated)
            ->with([
                'extraText'=> $this->extraText,
                'fileTranslated'=> $this->fileTranslated,
                'mailType'=>$this->mailType
            ]);

    }
}
