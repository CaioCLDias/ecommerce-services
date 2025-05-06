<?php

namespace App\Console\Commands;

use App\Constants\RabbitQueues;
use App\Http\Services\OrderService;
use App\Http\Services\RabbitMQService;
use Illuminate\Console\Command;

class OrderStatusConsumerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:status-consume';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consume order status to update the order status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Waiting for order status updates...');

        $rabbit = new RabbitMQService();

        $rabbit->setQueue(RabbitQueues::ORDER_STATUS_UPDATE);

        $orderService = new OrderService();

        $rabbit->consume(function($msg) use ($orderService) {
            $data = json_decode($msg->body, true);

            if(!$data){
                $this->error('Invalid order status data');
                return;
            }

            $this->info('Processing order status update...');

            $order = $orderService->updateStatus($data['order_id'], $data['status']);

            if($order){
                $this->info('Order status updated successfully');
            }else{
                $this->error('Failed to update order status');
            }
        });
    }
}
