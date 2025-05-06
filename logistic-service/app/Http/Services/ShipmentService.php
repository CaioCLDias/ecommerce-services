<?php

namespace App\Http\Services;

use App\Constants\RabbitQueues;
use App\Http\Services\RabbitMQService;
use App\Models\Shipment;

class ShipmentService
{

    public function createShipment($data)
    {
        return Shipment::create([
            'order_id' => $data['order_id'],
            'user_id' => $data['user_id'],
            'address' => json_encode($data['address']),
            'items' => json_encode($data['items']),
            'status' => 'processing',
        ]);
    }
    public function updateStatus($id, $status)
    {
        $shipment = Shipment::find($id);

        if (!$shipment) {
            return false;
        }

        $shipment->status = $status;
        $shipment->save();

        $rabbit = new RabbitMQService();
        $rabbit->setQueue(RabbitQueues::ORDER_STATUS_UPDATE);

        $message = [
            'order_id' => $shipment->order_id,
            'status' => $shipment->status,
        ];

        $rabbit->publish($message);

        return $shipment;
    }
}