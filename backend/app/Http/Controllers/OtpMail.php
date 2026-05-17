<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable {
    use Queueable, SerializesModels;

    public string $otp;
    public string $type;
    public string $userName;

    public function __construct(string $otp, string $type = 'login', string $userName = 'User') {
        $this->otp = $otp;
        $this->type = $type;
        $this->userName = $userName;
    }

    public function build() {
        $subject = $this->type === 'login'
            ? 'Kode OTP Login - Fitnezz Gym'
            : 'Kode OTP Reset Password - Fitnezz Gym';

        return $this->subject($subject)
                    ->view('emails.otp');
    }
}