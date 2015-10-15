@extends('email')

@section('content')

<p>Hi {{$user->first_name}}</p>

<p>Someone has been trying to login to your account and it is now locked. You can login and unlock it by <a href="{{url()}}/#/login/{{$user->remember_token}}">clicking here</a></p>

<p>If this wasn't you we highly recommend changing your password to one that is very secure (a new one with lots of characters and different cases) as well as turning on two-factor-authentication. Please contact us if you have any questions.</p>

@endsection