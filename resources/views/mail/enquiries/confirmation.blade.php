<x-mail::message>
# Thank You for Reaching Out!

Dear {{ $enquiry->name }},

We've successfully received your enquiry and appreciate you taking the time to contact us. Our team will review your message and get back to you as soon as possible—typically within 1-2 business days.

<x-mail::panel>
### Enquiry Details
For your reference, here's a summary of the information you submitted:

<x-mail::table>
| Field       | Details                  |
|-------------|--------------------------|
| **Subject** | {{ $enquiry->subject }} |
| **Email**   | {{ $enquiry->email }}   |
| **Phone**   | {{ $enquiry->phone ?? 'Not provided' }} |
| **Submitted On** | {{ $enquiry->created_at->format('M d, Y g:i A') }} |
</x-mail::table>

**Your Message Preview:**  
{{ Str::limit($enquiry->message, 150, '...') }}  
*(We've truncated the message for brevity in this confirmation. Rest assured, we have the full details.)*
</x-mail::panel>

If you need to update your enquiry or provide additional information, feel free to reply to this email.

<x-mail::button :url="config('app.url')">
Visit Our Website
</x-mail::button>

Best regards,  
{{ config('app.name') }} Team  
</x-mail::message>