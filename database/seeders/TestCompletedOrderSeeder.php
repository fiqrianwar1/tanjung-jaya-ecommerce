<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class TestCompletedOrderSeeder extends Seeder
{
    public function run(): void
    {
        $customer = User::where('email', 'customer@tanjungjaya.com')->first();
        if (! $customer) {
            $this->command->error('Customer not found!');

            return;
        }

        $product = Product::first();
        if (! $product) {
            $this->command->error('Product not found! Run product seeder first.');

            return;
        }

        // Clean up previous test orders for this user to avoid clutter
        Order::where('user_id', $customer->id)->delete();

        $order = Order::create([
            'user_id' => $customer->id,
            'total' => $product->price * 2,
            'status' => 'completed',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'qty' => 2,
            'price' => $product->price,
        ]);

        $this->command->info('Test order created successfully for customer.');
    }
}
