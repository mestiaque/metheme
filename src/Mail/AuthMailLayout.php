<?php


namespace ME\Mail;

use Illuminate\Mail\Mailable;

class AuthMailLayout extends Mailable
{
    public $content;
    public $otp;
    public $companyName;
    public $companyLogo;
    public $currentYear;

    public function __construct($content, $otp = null)
    {
        $this->content = $content;
        $this->otp = $otp;
        $this->companyName = get_setting('app_name', config('app.name', 'M.ESTIAQUE'));
        $this->companyLogo = route('app_logo.show');
        $this->currentYear = date('Y');
    }

    public function build()
    {
        return $this->subject('Email Notification')
            ->view('me::mail.auth-layout');
    }
}
