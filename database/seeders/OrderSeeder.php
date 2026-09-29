<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        Order::factory(30)->create()->each(function ($order) {
            $items = OrderItem::factory(rand(1, 4))->make(['order_id' => $order->id]);
            $order->orderItems()->saveMany($items);

            $order->total = $items->sum(function ($item) {
                return $item->qty * $item->price;
            }) + $order->shipping_cost;
            $order->save();
        });
    }
}
