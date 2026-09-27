<?php

namespace ME\Mail;

use Illuminate\Mail\Mailable;

/**
 * One mailable for every Metheme mail template.
 *
 * Usually used through the helper:
 *     me_mail('user@example.com', 'Subject', '<p>Hello</p>');                       // common layout ("default")
 *     me_mail($email, 'Your code', '<p>Use this code</p>', ['otp' => 123456], 'auth'); // "auth" template
 *
 * Templates are registered in config('me_settings.mail_templates') (name => Blade view);
 * any existing Blade view name can also be passed directly.
 */
class TemplateMail extends Mailable
{
    public array $templateData;

    public function __construct(public string $templateView, string $subjectLine, array $data = [])
    {
        $this->subject($subjectLine);

        $this->templateData = array_merge([
            'title'        => $subjectLine,
            'content'      => '',
            'otp'          => null,
            'companyName'  => get_setting('app_name', config('app.name')),
            'companyLogo'  => route('app_logo.show'),
            'currentYear'  => date('Y'),
            'showGreeting' => false,
            'greetings'    => [
                'Best wishes from the team!',
                'Take care and stay healthy!',
                'Warm regards!',
                'Stay blessed!',
            ],
        ], $data);
    }

    public function build()
    {
        return $this->view($this->templateView, $this->templateData);
    }
}
