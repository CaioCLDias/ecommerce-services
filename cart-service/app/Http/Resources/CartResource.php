<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $items = $this->items ?? [];

        $formattedItems = [];
        $totalAmount = 0;
        $totalItems = 0;

        foreach ($items as $item) {
            $quantity = $item['quantity'] ?? 1;
            $price = (float) ($item['price'] ?? 0);
            $total = $quantity * $price;

            $formattedItems[] = [
                'product_id' => $item['id'] ?? null,
                'name'       => $item['name'] ?? null,
                'image'      => $item['image'] ?? null,
                'price'      => $price,
                'quantity'   => $quantity,
                'total'      => round($total, 2),
            ];

            $totalAmount += $total;
            $totalItems += $quantity;
        }

        return [
            'items'        => $formattedItems,
            'total_items'  => $totalItems,
            'total_amount' => round($totalAmount, 2),
        ];
    }
    
}
