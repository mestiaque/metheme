<?php

namespace ME\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    /**
     * Get a setting value by key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        // "sms_permit" is the decoded sms_notifications checkboxes (create/edit/payment/reminder)
        if ($key === 'sms_permit') {
            $value = static::get('sms_notifications');
            return is_array($value) ? $value : (json_decode($value ?? '', true) ?: ($default ?? []));
        }

        $setting = static::where('key', $key)->first();

        return $setting ? $setting->value : $default;
    }

    /**
     * Set a setting value
     *
     * @param string $key
     * @param mixed $value
     * @return \App\Models\Setting
     */
    public static function set(string $key, $value)
    {
        $setting = static::firstOrCreate(['key' => $key]);
        $setting->value = is_array($value) ? json_encode($value) : $value;
        $setting->save();

        return $setting;
    }
}
