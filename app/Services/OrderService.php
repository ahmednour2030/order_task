<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderService
{
    /**
     * @param array $data
     * @param int $userId
     * @return Order
     * @throws \Throwable
     */
    public function create(array $data, int $userId): Order
    {
        return DB::transaction(function () use ($data, $userId) {

            $order = Order::create([
                'user_id' => $userId,
                'status' => 'pending',
                'total_amount' => 0,
            ]);

            $total = 0;

            foreach ($data['items'] as $item) {
                $subtotal = $item['quantity'] * $item['price'];
                $total += $subtotal;

                $order->items()->create([
                    'product_id'   => $item['product_id'] ?? null,
                    'product_name' => $item['product_name'],
                    'quantity'     => $item['quantity'],
                    'price'        => $item['price'],
                    'subtotal'     => $subtotal,
                ]);
            }

            $order->update([
                'total_amount' => $total,
            ]);

            return $order->load('items');
        });
    }

    /**
     * @param Order $order
     * @param array $data
     * @return Order
     * @throws \Throwable
     */
    public function update(Order $order, array $data): Order
    {
        if ($order->status !== 'pending') {
            throw new \Exception('Only pending orders can be updated.');
        }

        return DB::transaction(function () use ($order, $data) {

            $existingItemIds = $order->items()->pluck('id')->toArray();
            $newItemIds = collect($data['items'])
                ->pluck('id')
                ->filter()
                ->toArray();

            // حذف items اللي اتشالت
            $itemsToDelete = array_diff($existingItemIds, $newItemIds);
            $order->items()->whereIn('id', $itemsToDelete)->delete();

            $total = 0;

            foreach ($data['items'] as $item) {
                $subtotal = $item['quantity'] * $item['price'];
                $total += $subtotal;

                // Update existing item
                if (isset($item['id'])) {
                    $order->items()
                        ->where('id', $item['id'])
                        ->update([
                            'product_id'   => $item['product_id'] ?? null,
                            'product_name' => $item['product_name'],
                            'quantity'     => $item['quantity'],
                            'price'        => $item['price'],
                            'subtotal'     => $subtotal,
                        ]);
                }
                // Create new item
                else {
                    $order->items()->create([
                        'product_id'   => $item['product_id'] ?? null,
                        'product_name' => $item['product_name'],
                        'quantity'     => $item['quantity'],
                        'price'        => $item['price'],
                        'subtotal'     => $subtotal,
                    ]);
                }
            }

            $order->update([
                'total_amount' => $total,
            ]);

            return $order->load('items');
        });
    }

    /**
     * @param Order $order
     * @return void
     * @throws \Throwable
     */
    public function delete(Order $order): void
    {
        if ($order->payments()->exists() && !$order->user_id === auth('api')->id()) {
            throw new \Exception(
                'Order cannot be deleted because it has associated payments.'
            );
        }

        DB::transaction(function () use ($order) {

            $order->items()->delete();

            $order->delete();
        });
    }
}
