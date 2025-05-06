<?php

namespace App\Console\Commands;

use App\Constants\RabbitQueues;
use App\Http\Services\OrderService;
use App\Http\Services\RabbitMQService;
use Illuminate\Console\Command;

class OrderConsumerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:consume';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command orders from RabbitMQ';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Wating for orders...');

        $rabbit = new RabbitMQService();

        $rabbit->setQueue(RabbitQueues::ORDER);

        $orderService = new OrderService();

        $rabbit->consume(function($msg) use ($orderService) {
           
            $data = json_decode($msg->body, true);

            if(!$data){
                $this->error('Invalid order data');
                return;
            }

            $this->info('Processing order...');

            $order = $orderService->createOrder($data);

            if($order){
                $this->info('Order processed successfully');
            }else{
                $this->error('Failed to process order');
            }
        });

    }
}
