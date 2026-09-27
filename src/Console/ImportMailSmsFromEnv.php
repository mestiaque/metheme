<?php

namespace ME\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;
use ME\Models\Setting;

/**
 * One-time move of MAIL_* / SMS_* values from .env into the settings table
 * (Mail / SMS Configuration pages). Passwords and API keys are stored encrypted.
 * Only empty database settings are filled unless --force is given. Values are never printed.
 */
class ImportMailSmsFromEnv extends Command
{
    protected $signature = 'metheme:import-mail-sms-env {--force : Overwrite settings that already have a value}';

    protected $description = 'Copy mail and SMS settings from .env into the database (Mail / SMS Configuration)';

    public function handle(): int
    {
        $map = [
            'mail_mailer'       => ['MAIL_MAILER', false],
            'mail_host'         => ['MAIL_HOST', false],
            'mail_port'         => ['MAIL_PORT', false],
            'mail_encryption'   => ['MAIL_ENCRYPTION', false],
            'mail_username'     => ['MAIL_USERNAME', false],
            'mail_password'     => ['MAIL_PASSWORD', true],
            'mail_from_address' => ['MAIL_FROM_ADDRESS', false],
            'mail_from_name'    => ['MAIL_FROM_NAME', false],
            'sms_api_url'       => ['SMS_API_URL', false],
            'sms_api_key'       => ['SMS_API_KEY', true],
            'sms_sender_id'     => ['SMS_SENDER_ID', false],
            'sms_balance_url'   => ['SMS_BALANCE_URL', false],
        ];

        $imported = [];
        $skipped = [];

        foreach ($map as $key => [$envKey, $encrypt]) {
            $value = env($envKey);

            if ($value === null || $value === '') {
                continue;
            }

            if (filled(Setting::get($key)) && !$this->option('force')) {
                $skipped[] = $key;
                continue;
            }

            if ($key === 'mail_encryption') {
                $value = in_array(strtolower((string) $value), ['ssl', 'smtps'], true) ? 'ssl' : (in_array(strtolower((string) $value), ['tls', 'starttls'], true) ? 'tls' : 'none');
            }

            if ($key === 'mail_mailer' && !in_array($value, ['smtp', 'sendmail', 'log'], true)) {
                $value = 'smtp';
            }

            if ($key === 'mail_from_name' && str_contains((string) $value, '${APP_NAME}')) {
                $value = str_replace('${APP_NAME}', (string) config('app.name'), (string) $value);
            }

            Setting::set($key, $encrypt ? Crypt::encryptString((string) $value) : $value);
            $imported[] = $key;
        }

        // Port 465 means implicit SSL even when MAIL_ENCRYPTION was not set
        if (in_array('mail_port', $imported, true) && (int) Setting::get('mail_port') === 465 && !in_array('mail_encryption', $imported, true)) {
            Setting::set('mail_encryption', 'ssl');
            $imported[] = 'mail_encryption';
        }

        if (in_array('sms_api_url', $imported, true)) {
            Setting::set('enable_sms', true);
            $imported[] = 'enable_sms';
        }

        $this->info('Imported: ' . ($imported ? implode(', ', $imported) : 'nothing'));
        if ($skipped) {
            $this->line('Kept existing database values (use --force to overwrite): ' . implode(', ', $skipped));
        }
        $this->line('Mail and SMS now use the database settings only. You can remove MAIL_* / SMS_* from .env.');

        return self::SUCCESS;
    }
}
