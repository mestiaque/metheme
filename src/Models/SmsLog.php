<?php

namespace ME\Models;

use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    protected $fillable = [
        'to',
        'message',
        'status',
        'response_code',
        'api_response',
    ];
}
