<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Address extends Model
{

    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'street',
        'number',
        'city',
        'state',
        'postal_code',
        'country',
        'neighborhood',
        'complement',
        'is_default'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
