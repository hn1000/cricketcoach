<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Company;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Staff;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = Company::where('is_active', true)->get();

        if ($companies->isEmpty()) {
            $this->command->error('No active companies found. Please run CompanySeeder first.');
            return;
        }

        $statuses = ['pending_payment', 'confirmed', 'completed', 'cancelled'];
        $customers = [
            ['name' => 'John Smith', 'email' => 'john.smith@example.com', 'phone' => '555-0101'],
            ['name' => 'Sarah Johnson', 'email' => 'sarah.j@example.com', 'phone' => '555-0102'],
            ['name' => 'Michael Brown', 'email' => 'm.brown@example.com', 'phone' => '555-0103'],
            ['name' => 'Emily Davis', 'email' => 'emily.davis@example.com', 'phone' => '555-0104'],
            ['name' => 'David Wilson', 'email' => 'd.wilson@example.com', 'phone' => '555-0105'],
            ['name' => 'Jessica Martinez', 'email' => 'j.martinez@example.com', 'phone' => '555-0106'],
            ['name' => 'Robert Taylor', 'email' => 'r.taylor@example.com', 'phone' => '555-0107'],
            ['name' => 'Lisa Anderson', 'email' => 'lisa.a@example.com', 'phone' => '555-0108'],
        ];

        $this->command->info('Creating bookings...');

        foreach ($companies as $company) {
            $staff = Staff::where('company_id', $company->id)
                ->where('is_active', true)
                ->get();

            if ($staff->isEmpty()) {
                continue;
            }

            // Create 10-15 bookings per company
            $bookingCount = rand(10, 15);

            for ($i = 0; $i < $bookingCount; $i++) {
                $customer = $customers[array_rand($customers)];
                $selectedStaff = $staff->random();
                $status = $statuses[array_rand($statuses)];

                // Random date in the past 30 days or next 60 days
                $daysOffset = rand(-30, 60);
                $bookingDate = Carbon::now()->addDays($daysOffset)->format('Y-m-d');

                // Random time slot
                $startHour = rand(9, 16);
                $startMinute = [0, 15, 30, 45][rand(0, 3)];
                $duration = [30, 45, 60][rand(0, 2)];

                $startTime = sprintf('%02d:%02d:00', $startHour, $startMinute);
                $endTime = Carbon::createFromFormat('H:i:s', $startTime)
                    ->addMinutes($duration)
                    ->format('H:i:s');

                // Random price
                $price = [30.00, 40.00, 50.00, 75.00, 100.00][rand(0, 4)];

                // Determine order status based on booking status
                $orderStatus = match ($status) {
                    'pending_payment' => 'pending',
                    'confirmed', 'completed' => 'paid',
                    'cancelled' => 'cancelled',
                    default => 'pending',
                };

                // Create the order
                $order = Order::create([
                    'customer_name' => $customer['name'],
                    'customer_email' => $customer['email'],
                    'customer_phone' => $customer['phone'],
                    'company_id' => $company->id,
                    'order_type' => 'booking',
                    'status' => $orderStatus,
                    'subtotal' => $price,
                    'tax' => round($price * 0.1, 2),
                    'total' => round($price * 1.1, 2),
                    'currency' => 'USD',
                ]);

                // Create the booking
                $booking = Booking::create([
                    'order_id' => $order->id,
                    'staff_id' => $selectedStaff->id,
                    'company_id' => $company->id,
                    'booking_date' => $bookingDate,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'duration' => $duration,
                    'price' => $price,
                    'status' => $status,
                    'customer_name' => $customer['name'],
                    'customer_email' => $customer['email'],
                    'customer_phone' => $customer['phone'],
                    'notes' => rand(0, 1) ? 'Looking forward to the session!' : null,
                    'confirmation_token' => Str::random(32),
                    'cancelled_at' => $status === 'cancelled' ? Carbon::now()->subDays(rand(1, 10)) : null,
                    'cancellation_reason' => $status === 'cancelled' ? 'Schedule conflict' : null,
                ]);

                // Create order item
                OrderItem::create([
                    'order_id' => $order->id,
                    'orderable_type' => Booking::class,
                    'orderable_id' => $booking->id,
                    'quantity' => 1,
                    'unit_price' => $price,
                    'total_price' => $price,
                    'metadata' => [
                        'staff_name' => $selectedStaff->first_name . ' ' . $selectedStaff->last_name,
                        'booking_date' => $bookingDate,
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'duration' => $duration,
                    ],
                ]);

                // Create payment record for paid orders
                if ($orderStatus === 'paid') {
                    Payment::create([
                        'order_id' => $order->id,
                        'payment_gateway' => 'stripe',
                        'gateway_transaction_id' => 'pi_test_' . Str::random(24),
                        'amount' => $order->total,
                        'currency' => 'USD',
                        'status' => 'succeeded',
                        'payment_method' => 'card',
                        'processed_at' => Carbon::now()->subMinutes(rand(1, 60)),
                        'metadata' => [
                            'customer_name' => $customer['name'],
                            'customer_email' => $customer['email'],
                        ],
                    ]);
                }
            }

            $this->command->info("Created {$bookingCount} bookings for {$company->name}");
        }

        $totalBookings = Booking::count();
        $this->command->info("Total bookings created: {$totalBookings}");
    }
}
