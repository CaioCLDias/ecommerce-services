<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartRequest;
use App\Http\Resources\CartResource;
use App\Http\Resources\DefaultResource;
use App\Http\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function show(Request $request)
    {
        try {
            $userId = $request->get('user_id');

            if (!$userId) {
                return new DefaultResource(null, false, 400, 'User ID is required');
            }

            $cart = $this->cartService->getByUser($userId);

            return new DefaultResource(new CartResource($cart), true, 200, 'Cart retrieved successfully');
        } catch (\Exception $e) {
            return new DefaultResource(null, false, 500, $e->getMessage());
        }
    }

    public function store(CartRequest $request)
    {
        try {

            $cart = $this->cartService->addItem($request->validated());

            if (!$cart) {
                return new DefaultResource(null, false, 404, 'Product not found');
            }

            return new DefaultResource(new CartResource($cart), true, 200, 'Item added to cart successfully');

        } catch (\Exception $e) {
            return new DefaultResource(null, false, 500, $e->getMessage());
        }
    }

    public function remove(Request $request, $productId)
    {
        try {
            $userId = $request->get('user_id');
            $cart = $this->cartService->removeItem($userId, $productId);
            if (!$cart) {
                return new DefaultResource(null, false, 404, 'Product not found in cart');
            }

            return new DefaultResource(new CartResource($cart), true, 200, 'Item removed from cart successfully');
        } catch (\Exception $e) {
            return new DefaultResource(null, false, 500, $e->getMessage());
        }
    }

    public function clear(Request $request)
    {
        try {
            $userId = $request->get('user_id');
            $cart = $this->cartService->clearCart($userId);

            return new DefaultResource(new CartResource($cart), true, 200, 'Cart cleared successfully');
        } catch (\Exception $e) {
            return new DefaultResource(null, false, 500, $e->getMessage());
        }
    }

    public function checkout(Request $request)
    {
        try {
            $userId = $request->get('user_id');
            $payment_method = $request->get('payment_method');

            $order = $this->cartService->checkout($userId, $payment_method);
            

            if (!$order) {
                return new DefaultResource(null, false, 404, 'Cart is empty');
            }

            return new DefaultResource($order, true, 200, 'Checkout successful');
        } catch (\Exception $e) {
            return new DefaultResource(null, false, 500, $e->getMessage());
        }
    }
}
