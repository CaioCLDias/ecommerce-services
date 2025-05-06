<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Resources\AddressResource;
use App\Http\Resources\DefaultResource;
use App\Http\Services\AddressService;

class AddressController extends Controller
{
    protected $addressService;
    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }
    
    public function show($userId)
    {
        try {
            $address = $this->addressService->getAddress($userId);
            return new DefaultResource(
                new AddressResource($address),
                true,
                200,
                'Address fetched successfully.',
            );
        } catch (\Exception $e) {
            return new DefaultResource(null, false, 500, $e->getMessage());
        }
    }

    public function store(AddressRequest $request)
    {
        try {
            $address = $this->addressService->createAddress($request->all());
            return new DefaultResource(
                new AddressResource($address),
                true,
                201,
                'Address created successfully.',
            );
        } catch (\Exception $e) {
            return new DefaultResource(null, false, 500, $e->getMessage());
        }
    }
        
}
