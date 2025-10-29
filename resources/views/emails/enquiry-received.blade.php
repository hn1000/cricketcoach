<x-mail::message>
# New Enquiry Received

Hi {{ $staff->first_name }},

You have a new coaching enquiry from **{{ $customer->name }}**.

**Customer Details:**
- **Email:** {{ $customer->email }}
- **Phone:** {{ $customer->phone ?? 'Not provided' }}

@if($enquiry->preferred_date || $enquiry->preferred_time_slot)
**Preferred Timing:**
@if($enquiry->preferred_date)
- **Date:** {{ $enquiry->preferred_date->format('l, F j, Y') }}
@endif
@if($enquiry->preferred_time_slot)
- **Time Slot:** {{ ucfirst($enquiry->preferred_time_slot) }}
@endif
@endif

**Message:**

{{ $enquiry->message }}

<x-mail::button :url="route('admin.enquiries.show', $enquiry->id)">
View Enquiry
</x-mail::button>

Please respond to {{ $customer->name }} as soon as possible.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
