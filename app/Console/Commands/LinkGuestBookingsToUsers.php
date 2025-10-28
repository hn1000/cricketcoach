<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Order;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class LinkGuestBookingsToUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:link-guests
                            {--dry-run : Run without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Link existing guest bookings to registered users based on matching email addresses';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->info('Running in DRY RUN mode - no changes will be made');
        }

        $this->info('Searching for bookings and orders with null user_id...');

        // Find bookings without a user_id but with a customer_email
        $bookings = Booking::whereNull('user_id')
            ->whereNotNull('customer_email')
            ->get();

        // Find orders without a user_id but with a customer_email
        $orders = Order::whereNull('user_id')
            ->whereNotNull('customer_email')
            ->get();

        if ($bookings->isEmpty() && $orders->isEmpty()) {
            $this->info('No bookings or orders found that need linking.');
            return Command::SUCCESS;
        }

        $bookingLinked = 0;
        $orderLinked = 0;
        $notFound = [];

        DB::beginTransaction();

        try {
            // Process bookings
            foreach ($bookings as $booking) {
                $user = User::where('email', $booking->customer_email)->first();

                if ($user) {
                    $this->line("Booking #{$booking->id} - Email: {$booking->customer_email} -> User #{$user->id} ({$user->name})");

                    if (!$dryRun) {
                        $booking->update(['user_id' => $user->id]);
                    }
                    $bookingLinked++;
                } else {
                    $notFound[] = "Booking #{$booking->id} - {$booking->customer_email}";
                }
            }

            // Process orders
            foreach ($orders as $order) {
                $user = User::where('email', $order->customer_email)->first();

                if ($user) {
                    $this->line("Order #{$order->id} - Email: {$order->customer_email} -> User #{$user->id} ({$user->name})");

                    if (!$dryRun) {
                        $order->update(['user_id' => $user->id]);
                    }
                    $orderLinked++;
                } else {
                    $notFound[] = "Order #{$order->id} - {$order->customer_email}";
                }
            }

            if (!$dryRun) {
                DB::commit();
                $this->newLine();
                $this->info("Successfully linked {$bookingLinked} bookings and {$orderLinked} orders to users.");
            } else {
                DB::rollBack();
                $this->newLine();
                $this->info("Would link {$bookingLinked} bookings and {$orderLinked} orders (DRY RUN).");
            }

            if (!empty($notFound)) {
                $this->newLine();
                $this->warn('The following bookings/orders could not be linked (no matching user found):');
                foreach ($notFound as $item) {
                    $this->line('  - ' . $item);
                }
            }

            return Command::SUCCESS;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Error linking bookings/orders: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
