<?php

namespace ME\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use ME\Models\SmsAccount;
use ME\Models\SmsLog;

/**
 * Sends one SMS through the gateway saved on the SMS Configuration page
 * (config: services.sms_api_url / sms_api_key / sms_sender_id).
 *
 * Gateway protocol: form POST with api_key, senderid, number, message;
 * a response_code of 202 means the gateway accepted the message.
 *
 * Every attempt is written to sms_logs. The local balance (sms_accounts) must cover
 * the per-SMS rate, and is charged only when the gateway accepts the message.
 */
class SmsService
{
    private const GATEWAY_BALANCE_CACHE_KEY = 'me.sms.gateway_balance';

    /**
     * @return array{success: bool, response_code: mixed, response: mixed, error: ?string}
     */
    public static function send(string $to, string $message): array
    {
        $result = self::attempt($to, $message);

        SmsLog::create([
            'to'            => $to,
            'message'       => $message,
            'status'        => $result['success'] ? 'success' : ($result['response_code'] === null ? 'error' : 'failed'),
            'response_code' => $result['response_code'],
            'api_response'  => $result['response'] !== null ? json_encode($result['response'], JSON_UNESCAPED_UNICODE) : $result['error'],
        ]);

        if ($result['success']) {
            // Atomic, so two SMS sent at the same moment can't both read the old balance
            SmsAccount::query()->limit(1)->update([
                'sms_used' => DB::raw('sms_used + 1'),
                'balance'  => DB::raw('balance - sms_rate'),
            ]);
            Cache::forget(self::GATEWAY_BALANCE_CACHE_KEY);
        }

        return $result;
    }

    /**
     * Remaining balance reported by the SMS provider (Balance API URL on the SMS Configuration page).
     * Cached for 5 minutes; pass $refresh to ask the provider again.
     *
     * @return array{balance: ?float, error: ?string, checked_at: ?string}
     */
    public static function gatewayBalance(bool $refresh = false): array
    {
        $url = config('services.sms_balance_url');
        if (empty($url)) {
            return ['balance' => null, 'error' => null, 'checked_at' => null];
        }

        if ($refresh) {
            Cache::forget(self::GATEWAY_BALANCE_CACHE_KEY);
        }

        return Cache::remember(self::GATEWAY_BALANCE_CACHE_KEY, now()->addMinutes(5), function () use ($url) {
            try {
                $response = Http::timeout(10)->get($url, ['api_key' => config('services.sms_api_key')]);
                $body = $response->json();
                // Providers differ; take the first "balance" value anywhere in the JSON, or a bare number
                $balance = is_array($body)
                    ? collect(Arr::dot($body))->first(fn ($value, $key) => str_ends_with(strtolower($key), 'balance') && is_numeric($value))
                    : (is_numeric(trim($response->body())) ? trim($response->body()) : null);

                return [
                    'balance'    => $balance !== null ? (float) $balance : null,
                    'error'      => $balance === null ? __('me::me.gateway_balance_unreadable') . ' ' . str($response->body())->limit(120) : null,
                    'checked_at' => now()->toDateTimeString(),
                ];
            } catch (\Throwable $e) {
                return ['balance' => null, 'error' => $e->getMessage(), 'checked_at' => now()->toDateTimeString()];
            }
        });
    }

    private static function attempt(string $to, string $message): array
    {
        $url = config('services.sms_api_url');

        if (empty($url) || empty(config('services.sms_api_key'))) {
            return self::result(false, null, null, __('me::me.sms_gateway_not_configured'));
        }

        if (!preg_match('/^(?:01[3-9]\d{8}|\+8801[3-9]\d{8})$/', $to)) {
            return self::result(false, null, null, __('me::me.invalid_phone_number'));
        }

        $account = SmsAccount::current();
        if ($account->balance < $account->sms_rate) {
            return self::result(false, null, null, __('me::me.insufficient_sms_balance'));
        }

        try {
            $response = Http::asForm()->timeout(20)->post($url, [
                'api_key'  => config('services.sms_api_key'),
                'senderid' => config('services.sms_sender_id'),
                'number'   => $to,
                'message'  => $message,
            ]);

            $body = $response->json() ?? ['raw' => $response->body()];
            $code = $body['response_code'] ?? $response->status();

            return self::result((int) $code === 202, $code, $body, (int) $code === 202 ? null : ($body['error_message'] ?? $body['message'] ?? null));
        } catch (\Throwable $e) {
            return self::result(false, null, null, $e->getMessage());
        }
    }

    private static function result(bool $success, $code, $response, ?string $error): array
    {
        return ['success' => $success, 'response_code' => $code, 'response' => $response, 'error' => $error];
    }
}
