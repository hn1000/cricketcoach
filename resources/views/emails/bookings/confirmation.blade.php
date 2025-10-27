<x-mail::message>
# Booking Confirmed

Hello {{ $booking->customer_name }},

Your booking has been confirmed! We're looking forward to seeing you.

## Booking Details

**Company:** {{ $booking->company->name }}
**Staff Member:** {{ $booking->staff->name }}
**Date:** {{ $booking->booking_date->format('l, F j, Y') }}
**Time:** {{ $booking->start_time }} - {{ $booking->end_time }}
**Duration:** {{ $booking->duration_formatted }}
**Price:** ${{ $booking->price }}

@if($booking->notes)
**Notes:** {{ $booking->notes }}
@endif

<x-mail::panel>
**Booking Reference:** #{{ $booking->id }}
</x-mail::panel>

## What to Bring

Please arrive 5-10 minutes early and bring:
- Comfortable sports attire
- Water bottle
- Any personal equipment (if applicable)

## Need to Cancel?

If you need to cancel or reschedule, please contact us as soon as possible.

<x-mail::button :url="route('booking.confirmation', $booking->confirmation_token)">
View Booking Details
</x-mail::button>

If you have any questions, please don't hesitate to contact us.

Thanks,<br>
{{ $booking->company->name }}
</x-mail::message>
