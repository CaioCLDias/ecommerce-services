<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'transaction_id',
        'provider',
        'payment_method',
        'status',
        'details',
    ];

    protected $casts = [
        'details' => 'array',
    ];


}
