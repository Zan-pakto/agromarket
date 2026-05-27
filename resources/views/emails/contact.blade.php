<x-mail::message>
# New Contact Form Submission

You have received a new message from the contact form.

**Name:** {{ $data['name'] }}  
**Email:** {{ $data['email'] }}  
**Subject:** {{ $data['subject'] }}

**Message:**  
{{ $data['message'] }}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
