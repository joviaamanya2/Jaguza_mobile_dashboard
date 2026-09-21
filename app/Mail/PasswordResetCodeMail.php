<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $code;
    public string $name;

    /**
     * @param string $code The 6-digit verification code to email to the user.
     * @param string|null $name The user's display name, if known.
     */
    public function __construct(string $code, ?string $name = null)
    {
        $this->code = $code;
        $this->name = $name ?: 'there';
    }

    public function build()
    {
        return $this->subject('Your Jaguza password reset code')
            ->view('emails.password_reset_code');
    }
}
