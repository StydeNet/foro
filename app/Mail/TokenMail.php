<?php

namespace App\Mail;

use App\Token;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TokenMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Token
     */
    public $token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Token $token)
    {
        //
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.token');
    }
}
