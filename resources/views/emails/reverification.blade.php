@extends('email')

@section('content')

<p>Hi {{$user->first_name}}</p>

<p>Due to you recent changes to your profile we now require you to reverify your details. Please login to the site and submit Canadian government issue ID that confirms:</p>
<p>
	<strong>Name</strong> {{ $user->first_name }} {{ $user->last_name }}<br/>
	<strong>Birthday</strong> {{ $user->date_of_birth?$user->date_of_birth->format('F jS, o'):"" }}
</p>

@endsection