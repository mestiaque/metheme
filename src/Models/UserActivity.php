<?php

namespace ME\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserActivity extends Model
{
    protected $table = 'user_activities';

    protected $fillable = [
        'user_id',
        'activity_type',
        'ip_address',
        'browser_name',
        'browser_version',
        'device_name',
        'device_type',
        'os_name',
        'os_version',
        'user_agent',
        'country',
        'city',
        'latitude',
        'longitude',
        'status',
        'description',
        'activity_at',
        'url',
    ];

    protected $casts = [
        'activity_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user associated with this activity
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get activity type label
     */
    public function getActivityTypeLabel(): string
    {
        return match ($this->activity_type) {
            'login' => __('Login'),
            'logout' => __('Logout'),
            'registration' => __('Registration'),
            'forgot_password' => __('Password Reset Request'),
            'password_reset' => __('Password Reset'),
            'profile_update' => __('Profile Update'),
            default => ucfirst(str_replace('_', ' ', $this->activity_type)),
        };
    }

    /**
     * Get status badge color
     */
    public function getStatusColor(): string
    {
        return match ($this->status) {
            'success' => 'success',
            'failed' => 'danger',
            'pending' => 'warning',
            default => 'secondary',
        };
    }
}
