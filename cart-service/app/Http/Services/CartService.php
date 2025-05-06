<?php

namespace App\Http\Services;

use App\Constants\RabbitQueues;
use App\Models\Cart;
use Illuminate\Support\Facades\Http;


class CartService
{
    public function getByUser($userId)
    {
        return Cart::firstOrCreate(['user_id' => $userId]);
    }


    public function addItem(array $request)
    {
        $userId = $request['user_id'];
        $productId = $request['product_id'];

        $cart = Cart::firstOrCreate(['user_id' => $userId]);
        $items = $cart->items ?? [];

        $items = collect($items)->map(function ($item) use ($productId) {
            if ($item['id'] === (int) $productId) {
                $item['quantity'] += 1; // Sempre soma 1
                return $item;
            }
            return $item;
        });

        $exists = $items->contains(fn($item) => $item['id'] === (int) $productId);

        if (!$exists) {
            $response = Http::get(env('CATOLOG_SERVICE_URL') . '/products/' . $productId);
            $data = $response->json();

            if (
                $response->failed() ||
                !isset($data['status']) || $data['status'] !== true ||
                !isset($data['data']) || empty($data['data'])
            ) {
                return null;
            }

            $product = $data['data'];

            $items->push([
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => 1,
            ]);
        }

        $cart->items = $items->values()->all();
        $cart->save();

        return $cart;
    }

    public function removeItem($userId, $productId)
    {
        $cart = Cart::where('user_id', $userId)->first();

        if (!$cart || empty($cart->items)) {
            return false;
        }

        $items = collect($cart->items)->map(function ($item) use ($productId) {
            if ($item['id'] === (int) $productId) {
                if ($item['quantity'] > 1) {
                    $item['quantity'] -= 1;
                    return $item;
                }
                return null;
            }
            return $item;
        })->filter()->values()->all();

        $cart->items = $items;
        $cart->save();

        return $cart;
    }



    public function clearCart($userId)
    {
        $cart = Cart::where('user_id', $userId)->firstOrFail();
        $cart->items = [];
        $cart->save();

        return $cart;
    }

    public function checkout($userId, $paymentMethod)
    {
        $cart = Cart::where('user_id', $userId)->first();
        
        if (!$cart || empty($cart->items)) {
            return null;
        }

        $orderPayload = [
            'user_id' => $userId,
            'items' => $cart->items,
            'total_amount' => collect($cart->items)->sum(function ($item) {
                return $item['price'] * $item['quantity'];
            }),
            'payment_method' => $paymentMethod,
            'created_at' => now()->toDateTimeString()
        ];

        $rabbit = new RabbitMQService();
        $rabbit->setQueue(RabbitQueues::ORDER);
        $rabbit->publish($orderPayload);


        $cart->items = [];
        $cart->save();

        return $orderPayload;

    }
}
