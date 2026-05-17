<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Console\Command;

class SendOrderReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agromarket:send-order-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send automatic dispatch reminders to sellers and tracking updates to buyers for older pending orders.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Scanning pending and confirmed orders older than 24 hours...');

        $threshold = now()->subHours(24);
        $orders = Order::whereIn('order_status', ['pending', 'confirmed'])
            ->where('updated_at', '<', $threshold)
            ->get();

        $count = $orders->count();

        if ($count === 0) {
            $this->info('No delayed pending orders found.');
            return;
        }

        foreach ($orders as $order) {
            // 1. Send reminder to buyer (farmer)
            Notification::create([
                'user_id' => $order->user_id,
                'title' => 'Order Update Reminder',
                'message' => "Your order {$order->tracking_number} is currently being prepared. We will update you with a tracking number once it ships.",
                'type' => 'order',
            ]);

            // 2. Identify and notify each seller of items in this order
            if (is_array($order->items)) {
                $sellerIds = array_unique(array_filter(array_column($order->items, 'seller_id')));
                foreach ($sellerIds as $sellerId) {
                    $seller = User::find($sellerId);
                    if ($seller) {
                        Notification::create([
                            'user_id' => $seller->id,
                            'title' => 'URGENT: Order Dispatch Reminder',
                            'message' => "Order {$order->tracking_number} contains items from your store and has been pending for over 24 hours. Please confirm and ship it as soon as possible.",
                            'type' => 'order',
                        ]);
                    }
                }
            }
        }

        $this->info("Successfully sent dispatch reminders and tracking updates for {$count} pending orders.");
    }
}
