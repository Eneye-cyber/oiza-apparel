{{-- resources/views/mail/enquiries/enquiry-response.blade.php --}}

<x-mail::message>
# Response to Your Enquiry

Hello {{ $enquiry->name }},

Thank you for contacting us.

We have received your enquiry regarding: "{{ $enquiry->subject }}".

Our response: 
{!! $responseMessage !!}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>