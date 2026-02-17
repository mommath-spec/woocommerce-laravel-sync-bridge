<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'woo_id',
        'name',
        'sku',
        'price',
        'status',
        'raw_payload',
    ];

    protected $casts = [
        'raw_payload' => 'array',
        'price' => 'decimal:2',
    ];
}
