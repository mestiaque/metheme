<?php

namespace ME\Services;

use Illuminate\Mail\Events\MessageSending;
use Illuminate\Mail\Events\MessageSent;
use ME\Models\MailLog;
use Symfony\Component\Mime\Address;

/**
 * Records every email the app sends in mail_logs (registered in MEServiceProvider).
 * The body is deliberately not stored: password-reset mails carry login links.
 */
class MailLogger
{
    /** @var array<int, int> spl_object_id(message) => mail_logs.id, to match "sent" to its "sending" row */
    private static array $pending = [];

    public static function sending(MessageSending $event): void
    {
        try {
            $message = $event->message;
            $log = MailLog::create([
                'to'      => self::addresses($message->getTo()),
                'cc'      => self::addresses($message->getCc()) ?: null,
                'subject' => $message->getSubject(),
                'mailer'  => $event->data['mailer'] ?? config('mail.default'),
                'status'  => 'sending',
            ]);
            self::$pending[spl_object_id($message)] = $log->id;
        } catch (\Throwable $e) {
            // Logging must never stop an email (e.g. mail_logs not migrated yet)
        }
    }

    public static function sent(MessageSent $event): void
    {
        try {
            $key = spl_object_id($event->message);
            if (isset(self::$pending[$key])) {
                MailLog::whereKey(self::$pending[$key])->update(['status' => 'sent', 'sent_at' => now()]);
                unset(self::$pending[$key]);
            }
        } catch (\Throwable $e) {
            //
        }
    }

    private static function addresses(array $addresses): string
    {
        return mb_substr(implode(', ', array_map(fn (Address $a) => $a->getAddress(), $addresses)), 0, 500);
    }
}
