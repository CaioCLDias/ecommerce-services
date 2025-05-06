<?php

namespace App\Http\Services;

use App\Models\User;

class UserService
{
    public function create(array $data)
    {
        return User::create($data);
    }

    public function getAll()
    {
        return User::all();
    }

    public function getByid(int $id)
    {
        $user =  User::findOrFail($id);

        if (!$user) {
            throw new \Exception('Product not found');
        }

        return $user;
    }
}