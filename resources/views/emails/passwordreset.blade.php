@extends('email')

@section('content')

<p>Hi {{$user->first_name}}</p>

<p>Have you been having trouble logging in? Would you like to reset your password?</p>

<p><a href="{{url('/')}}/#/login/{{$user->remember_token}}">Reset Password</a></p>
 
@endsection