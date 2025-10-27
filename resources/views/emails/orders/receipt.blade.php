<x-mail::message>
# Order Receipt

Hello {{ $order->customer_name }},

Thank you for your order! Here are the details of your purchase.

<x-mail::panel>
**Order Number:** {{ $order->order_number }}
**Order Date:** {{ $order->created_at->format('F j, Y g:i A') }}
**Status:** {{ ucfirst($order->status) }}
</x-mail::panel>

## Order Items

<x-mail::table>
| Item | Quantity | Price | Total |
|:-----|:--------:|------:|------:|
@foreach($order->items as $item)
| {{ $item->orderable_type ? class_basename($item->orderable_type) : 'Item' }} #{{ $item->orderable_id }} | {{ $item->quantity }} | ${{ number_format($item->unit_price, 2) }} | ${{ number_format($item->total_price, 2) }} |
@endforeach
</x-mail::table>

## Order Summary

**Subtotal:** ${{ number_format($order->subtotal, 2) }}
**Tax:** ${{ number_format($order->tax, 2) }}
**Total:** ${{ number_format($order->total, 2) }} {{ $order->currency }}

@if($order->company)
**Company:** {{ $order->company->name }}
@endif

## Payment Information

@if($order->payment)
**Payment Status:** {{ ucfirst($order->payment->status) }}
**Payment Method:** {{ ucfirst($order->payment->payment_method) }}
@if($order->payment->gateway_transaction_id)
**Transaction ID:** {{ $order->payment->gateway_transaction_id }}
@endif
@endif

<x-mail::button :url="route('admin.orders.show', $order->id)">
View Order Details
</x-mail::button>

If you have any questions about this order, please contact us.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
