@extends('email')

@section('content')

<?php echo Setting::get('email.welcome'); ?>

@endsection