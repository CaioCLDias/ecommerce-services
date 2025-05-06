<?php

namespace App\Http\Controllers;

use App\Http\Resources\DefaultResource;
use App\Http\Services\ShipmentService;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    protected $shipmentService;

    public function __construct(ShipmentService $shipmentService)
    {
        $this->shipmentService = $shipmentService;
    }

    public function updateStatus($id, Request $request)
    {
       $shipment = $this->shipmentService->updateStatus($id, $request->status);

        if (!$shipment) {
            return response()->json(['message' => 'Shipment not found'], 404);
        }

        return new DefaultResource($shipment);
    }
}
