@extends('email')

@section('content')

<p>Hello,</p>

<p>Someone has been trying to login to the IserveU platform using this email address ({{ $event->credentials['email'] }}) however it's not in our system. If you were the person trying to login and you have definately signed up for the IserveU platform at some point you can add this email address to your account after logging in with your real account.</p>

<p>Regards,<br/>
The IserveU Crew</p>

@endsection