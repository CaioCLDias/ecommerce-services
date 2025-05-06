<?php

namespace App\Console\Commands;

use App\Constants\RabbitQueues;
use App\Http\Services\PaymentService;
use App\Http\Services\RabbitMQService;
use Illuminate\Console\Command;

class PaymentConsumerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:consume';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consume payment queue and process payments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Consuming payment queue...');

        $rabbit = new RabbitMQService();

        $rabbit->setQueue(RabbitQueues::PAYMENT);

        $paymentService = new PaymentService();

        $rabbit->consume(function ($message) use ($paymentService, $rabbit) {
            $this->info('Processing payment message...');


            $order = json_decode($message->body, true);

            if (!$order) {
                $this->error('Invalid order data');
                return;
            }

            $payment = $paymentService->processPayment($order);

            if (!$payment) {
                $this->error('Failed to process payment');
                return;
            }

            $this->info("Payment #{$payment->order_id} processed. Status: {$payment->status}");

            $rabbit->setQueue(RabbitQueues::ORDER_STATUS_UPDATE);
            
            $rabbit->publish(
                [
                    'order_id' => $payment->order_id,
                    'status' => $payment->status,
                    'payment_method' => $payment->payment_method,
                    'provider' => $payment->provider,
                ]
            );
        });
    }
}
