<?php

namespace ME\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Local SMS balance: admin records recharges, each successful SMS deducts sms_rate.
 * There is a single row.
 */
class SmsAccount extends Model
{
    protected $fillable = [
        'admin_recharge_amount',
        'sms_rate',
        'sms_used',
        'balance',
    ];

    public static function current(): self
    {
        return static::first() ?? new static(['admin_recharge_amount' => 0, 'sms_rate' => 0, 'sms_used' => 0, 'balance' => 0]);
    }

    public function smsRemaining(): int
    {
        return $this->sms_rate > 0 ? (int) floor($this->balance / $this->sms_rate) : 0;
    }
}
