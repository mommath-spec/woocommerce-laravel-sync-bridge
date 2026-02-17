<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'woo_id',
        'status',
        'total',
        'currency',
        'customer_woo_id',
        'raw_payload',
    ];

    protected $casts = [
        'raw_payload' => 'array',
        'total' => 'decimal:2',
    ];
}
