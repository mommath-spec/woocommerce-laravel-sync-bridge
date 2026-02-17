<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'woo_id',
        'email',
        'first_name',
        'last_name',
        'raw_payload',
    ];

    protected $casts = [
        'raw_payload' => 'array',
    ];
}
