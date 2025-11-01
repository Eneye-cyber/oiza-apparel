<x-mail::message>
# New Enquiry Alert!

Hello Admin,

A new enquiry has been submitted through the website. Please review the details below and respond promptly.

<x-mail::panel>
### Enquiry Summary
<x-mail::table>
| Field       | Details                  |
|-------------|--------------------------|
| **Name**    | {{ $enquiry->name }}    |
| **Email**   | {{ $enquiry->email }}   |
| **Phone**   | {{ $enquiry->phone ?? 'Not provided' }} |
| **Subject** | {{ $enquiry->subject }} |
| **Submitted On** | {{ $enquiry->created_at->format('M d, Y g:i A') }} |
</x-mail::table>

**Message:**  
{{ $enquiry->message }}
</x-mail::panel>

To view or manage this enquiry, log in to the admin dashboard.

<x-mail::button :url="route('filament.admin.resources.enquiries.view', $enquiry)">
View Enquiry in Dashboard
</x-mail::button>

Thank you for your attention,  
{{ config('app.name') }} System  
</x-mail::message>