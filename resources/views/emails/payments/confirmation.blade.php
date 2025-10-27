<x-mail::message>
# Payment Confirmed

Hello {{ $payment->order->customer_name }},

We've successfully received your payment!

<x-mail::panel>
**Payment Amount:** ${{ number_format($payment->amount, 2) }} {{ $payment->currency }}
**Payment Status:** {{ ucfirst($payment->status) }}
**Order Number:** {{ $payment->order->order_number }}
</x-mail::panel>

## Payment Details

**Payment Method:** {{ ucfirst($payment->payment_method) }}
**Payment Gateway:** {{ ucfirst($payment->payment_gateway) }}
@if($payment->gateway_transaction_id)
**Transaction ID:** {{ $payment->gateway_transaction_id }}
@endif
**Processed At:** {{ $payment->processed_at ? $payment->processed_at->format('F j, Y g:i A') : 'Processing' }}

## What's Next?

Your booking has been confirmed and you should receive a separate confirmation email with all the details.

<x-mail::button :url="route('admin.orders.show', $payment->order_id)">
View Order Details
</x-mail::button>

Thank you for your payment!

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
