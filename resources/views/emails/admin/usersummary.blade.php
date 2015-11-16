@extends('email')

@section('content')


@if(!$newUsers->isEmpty())
	<h2>New Users</h2>
	@foreach($newUsers as $newUser)
		<p><a href="<?=url()?>#/users/<?=$newUser->id?>">{{$newUser->first_name}} {{$newUser->last_name}}</a></p>
	@endforeach
@endif


@endsection