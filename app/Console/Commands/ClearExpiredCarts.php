<?php

namespace App\Console\Commands;

use App\Models\Cart;
use Illuminate\Console\Command;

class ClearExpiredCarts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agromarket:clear-carts {days=7 : The threshold in days for card inactivity}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear inactive user carts that have not been modified within the specified days.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->argument('days');
        $threshold = now()->subDays($days);

        $this->info("Searching for carts inactive since: {$threshold->toDateTimeString()}...");

        $inactiveCarts = Cart::where('updated_at', '<', $threshold)->get();
        $count = $inactiveCarts->count();

        if ($count === 0) {
            $this->info('No expired or inactive carts found.');
            return;
        }

        foreach ($inactiveCarts as $cart) {
            // Either delete the record, or clear the items array
            $cart->delete();
        }

        $this->info("Successfully purged {$count} inactive carts from the database.");
    }
}
