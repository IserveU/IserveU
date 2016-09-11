@extends('email')

@section('content')

@if($data['created_by_other'])
	<h3>An account has been created for you</h3>
@endif

<?php echo \Setting::get('email.welcome'); ?>


@endsection