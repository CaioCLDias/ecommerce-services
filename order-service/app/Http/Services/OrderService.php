<?php

namespace App\Http\Services;

use App\Constants\RabbitQueues;
use App\Http\Resources\DefaultResource;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class OrderService
{

    protected $validStatuses = [
        'pending',
        'pending_payment',
        'payment_failed',
        'paid',
        'processing',
        'shipped',
        'delivered',
        'completed',
        'canceled'
    ];

    public function createOrder($data)
    {
      return DB::transaction(function () use($data) {
        
            $order = Order::create([
                'user_id' => $data['user_id'],
                'total_amount' => $data['total_amount'],
                'status' => 'pending',
            ]);

            foreach ($data['items'] as $item) {
                $order->items()->create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            $rabbit = new RabbitMQService();
            $rabbit->setQueue(RabbitQueues::PAYMENT);
            $rabbit->publish([
                'order_id' => $order->id,
                'payment_method' => $data['payment_method'] ?? 'credit_card',
                'user_id' => $data['user_id'],
                'total_amount' => $data['total_amount'],
                'items' => $data['items'],
            ]);

            return $order;
          
      });
    }

    public function getOrders($userId)
    {
        return Order::where('user_id', $userId)->with('items')->get();
    }

    public function getOrder($orderId)
    {
        return Order::with('items')->find($orderId);
    }

    public function updateStatus($orderId, $status)
    {

        if (!in_array($status, $this->validStatuses)) {
           return false;
        }

        $order = Order::find($orderId);

        if (!$order) {
            return new DefaultResource(false, 404, 'Order not found');
        }

        $order->status = $status;
        $order->save();

        $this->notifyUser($order);

        if($order->status == 'paid'){
            $this->sendToLogistics($order);
        }

        return $order;
    }

    public function sendToLogistics($order)
    {
        
        $catologUrl = env('CATALOG_SERVICE_URL');

        try{

            $response = Http::get($catologUrl . '/api/addresses/' . $order->user_id);

            if(!$response->status()){
                throw new \Exception("No default address found");
            }
    
            $address = $response->json();

            $rabbit = new RabbitMQService();
            $rabbit->setQueue(RabbitQueues::LOGISTICS);
            $rabbit->publish([
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'total_amount' => $order->total_amount,
                'address' => $address,
                'items' => $order->items,
                'created_at' => $order->created_at,
            ]);
    
            echo "Order #{$order->id} sent to logistics\n";

        }catch(\Exception $e){
             echo "Logistics Error: {$e->getMessage()}\n";

            $rabbit = new RabbitMQService();
            $rabbit->setQueue(RabbitQueues::ERRORS);
            $rabbit->publish([
                'service' => 'order',
                'order_id' => $order->id,
                'message' => "Logistics Error: " . $e->getMessage(),
                'queue' => RabbitQueues::LOGISTICS, 
            ]);
        }

    }

    public function notifyUser($order)
    {

        $message = $this->generateNotification($order->status);

        if(!$message){
            return;
        }

        $rabbit = new RabbitMQService();
        $rabbit->setQueue(RabbitQueues::NOTIFICATION);
        $rabbit->publish([
            'user_id' => $order->user_id,
            'order_id' => $order->id,
            'status' => $order->status,
            'message' => $message,
            'created_at' => $order->created_at,
        ]);

        echo "Notification sent for order #{$order->id}: {$message}\n";
    }

    protected function generateNotification($status){

        return match($status) {
            'pending' => 'Your order is pending.',
            'pending_payment' => 'Estamos aguardando a confirmação do pagamento.',
            'payment_failed' => 'Houve um problema com o pagamento de seu pedido',
            'paid' => 'Seu pagamento foi aprovado e seu pedido está sendo processado.',
            'processing' => 'Seu pedido foi enviado para a transportadora.',
            'shipped' => 'Seu pedido está a caminho.',
            'delivered' => 'Seu pedido foi entregue.',
            'cancelado' => 'Seu pedido foi cancelado',
            default => null,
        };
    }
}