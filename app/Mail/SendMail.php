<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($dataMail){
        $this->data = $dataMail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
        if(!empty($this->data['title'])&&!empty($this->data['body'])){
            return $this->subject($this->data['title'])
                    ->view('admin.mail.send', ['body' => $this->data['body']]);
        }
    }
}
