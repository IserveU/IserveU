@extends('email')

@section('content')

	<p>A motion you have voted on has changed, you might want to make sure it hasn't changed to the point that your vote is now different</p>

	<p>View <a style="text-decoration: none; color:#1F1C22" href="{{url('/')}}/#/motion/{{$motion->id}}">{{$motion->title}}</a></p>

@endsection