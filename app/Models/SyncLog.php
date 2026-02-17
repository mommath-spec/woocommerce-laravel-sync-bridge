<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SyncLog extends Model
{
    protected $fillable = [
        'type',
        'fetched_count',
        'upserted_count',
        'duration_ms',
        'status',
        'message',
        'context',
    ];

    protected $casts = [
        'context' => 'array',
    ];
}
