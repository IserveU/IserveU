@extends('email')

@section('content')

<p>Welcome to the IserveU beta {{$user->first_name}},</p>

<p>In October IserveU aims to upgrade our government and make it work better for everyone with more informed decision makers and more meaningful input from the public on decisions.</p>

<p>We welcome you to join in and get started <a href="{{url()}}/#/login/{{$user->remember_token}}">by logging in here.</a></p>

<p>Regards,<br/>
The IserveU Crew</p>

@endsection