<?php

namespace ME\Mail;

use Illuminate\Mail\Mailable;

class NoticeMailLayout extends Mailable
{
    public $title;
    public $content;
    public $companyName;
    public $companyLogo;
    public $currentYear;
    public $greetings;
    public $showGreeting;

    public function __construct($data = [])
    {
        // Ensure $data is an array
        if (!is_array($data)) {
            $data = [];
        }

        // Set content (required)
        $this->content = $data['content'] ?? '';

        // Set title for email subject and header
        $this->title = $data['title'] ?? 'Notice';

        // Set company info with defaults
        $this->companyName = $data['companyName'] ?? get_setting('app_name', config('app.name', 'M.ESTIAQUE'));
        $this->companyLogo = $data['companyLogo'] ?? (function_exists('route') ? route('app_logo.show') : '');
        $this->currentYear = $data['currentYear'] ?? date('Y');

        // Set greeting preference
        $this->showGreeting = $data['showGreeting'] ?? false;

        // Default greetings if not provided
        $this->greetings = $data['greetings'] ?? [
            'Best wishes from the team!',
            'Take care and stay healthy!',
            'Warm regards!',
            'Stay blessed!',
            'Wishing you well!',
            'With love and care!',
        ];

        // Set any additional custom properties from data
        foreach ($data as $key => $value) {
            if (!property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    public function build()
    {
        return $this->subject($this->title)
            ->view('me::mail.notice-layout');
    }
}
