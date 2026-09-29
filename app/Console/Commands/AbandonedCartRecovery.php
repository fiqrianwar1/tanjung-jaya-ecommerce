<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

#[Signature('app:abandoned-cart-recovery')]
#[Description('Mengingatkan pelanggan tentang keranjang belanja (order pending) yang belum dicheckout lebih dari 24 jam')]
class AbandonedCartRecovery extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Abandoned Cart Recovery...');

        $abandonedOrders = Order::where('status', 'pending')
            ->where('updated_at', '<', now()->subHours(24))
            ->with(['user', 'orderItems.product'])
            ->get();

        if ($abandonedOrders->isEmpty()) {
            $this->info('No abandoned carts found.');

            return;
        }

        foreach ($abandonedOrders as $order) {
            // For now we just log it as a simulation. In a real app we'd use Mail::to($order->user->email)->send(new AbandonedCartMail($order));
            Log::info("Abandoned Cart Alert sent to User ID: {$order->user_id} (Order ID: {$order->id})");
            $this->line("Sent reminder to {$order->user->email} for Order #{$order->id}");

            // Optionally update the order to 'reminded' if we want to avoid spamming
            // $order->update(['status' => 'reminded']);
        }

        $this->info('Abandoned Cart Recovery finished.');
    }
}
