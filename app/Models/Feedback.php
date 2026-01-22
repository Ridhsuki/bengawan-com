<?php

namespace App\Models;

use App\Enums\FeedbackStatus;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $fillable = [
        'message',
        'ip_address',
        'user_agent',
        'status',
    ];

    protected $casts = [
        'status' => FeedbackStatus::class,
    ];
}
