<?php

namespace App\Http\Services;

use App\Models\Address;

class AddressService
{
    public function createAddress(array $data)
    {
        return Address::create($data);
    }


    public function getAddress(int $userId)
    {
        return Address::where('user_id', $userId)->firstOrFail();
    }

}