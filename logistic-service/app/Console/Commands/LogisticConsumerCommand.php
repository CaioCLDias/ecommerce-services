<?php

namespace App\Console\Commands;

use App\Constants\RabbitQueues;
use App\Http\Services\RabbitMQService;
use App\Http\Services\ShipmentService;
use Illuminate\Console\Command;


class LogisticConsumerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logistics:consume';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consume logistics queue and process shipments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Waiting for logistic orders...');

        $rabbit = new RabbitMQService();

        $rabbit->setQueue(RabbitQueues::LOGISTICS);

        $shipmentService = new ShipmentService();

        $rabbit->consume(function ($message) use ($shipmentService) {
            $data = json_decode($message->body, true);

            $data = json_decode($message->body, true);

            $this->info("Received order #{$data['order_id']} for logistics");

            $shipment = $shipmentService->createShipment($data);

            $this->info("Shipment #{$shipment->id} created. Status: {$shipment->status}");
        });
    }
}
