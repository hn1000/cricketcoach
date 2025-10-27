<x-mail::message>
# Booking Cancelled

Hello {{ $booking->customer_name }},

Your booking has been cancelled as requested.

## Cancelled Booking Details

**Company:** {{ $booking->company->name }}
**Staff Member:** {{ $booking->staff->name }}
**Date:** {{ $booking->booking_date->format('l, F j, Y') }}
**Time:** {{ $booking->start_time }} - {{ $booking->end_time }}
**Price:** ${{ $booking->price }}

@if($booking->cancellation_reason)
**Cancellation Reason:** {{ $booking->cancellation_reason }}
@endif

<x-mail::panel>
**Booking Reference:** #{{ $booking->id }}
**Cancelled At:** {{ $booking->cancelled_at->format('F j, Y g:i A') }}
</x-mail::panel>

## Refund Information

If you made a payment, a refund will be processed according to our cancellation policy and should appear in your account within 5-10 business days.

## Book Again

We hope to see you again soon!

<x-mail::button :url="route('booking.create', ['company' => $booking->company_id, 'staff' => $booking->staff_id])">
Make a New Booking
</x-mail::button>

If you have any questions about this cancellation, please contact us.

Thanks,<br>
{{ $booking->company->name }}
</x-mail::message>
