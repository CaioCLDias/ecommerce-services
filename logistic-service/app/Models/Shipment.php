<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $fillable = [
        'order_id', 'user_id', 'address', 'items', 'status'
    ];

    protected $casts = [
        'address' => 'array',
        'items' => 'array',
    ];
}
