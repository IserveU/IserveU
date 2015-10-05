@extends('email')

@section('content')


@if(!$closingSoonMotions->isEmpty())
	<h2>Closing Soon</h2>
	@foreach($closingSoonMotions as $motion)
		<div>
			@if(isset($motion->motion_rank) && $motion->motion_rank>0)
				<img style="width:10%; padding-right:15px; padding-bottom:15px; float: left" src="<?=asset('/themes/'.config('app.themename').'/icons/arrow-top-right.png')?>">
			@else
				<img src="<?=asset('/themes/'.config('app.themename').'/icons/arrow-bottom-right.png')?>">
			@endif
			<h4><a style="text-decoration: none; color:#1F1C22" href="<?=base_path('motions/'.$motion->id)?>">{{$motion->title}}</a></h4>
			<p>{{$motion->summary}}</p>
		</div>
	@endforeach
@endif

@if(!$latestLaunchedMotions->isEmpty())
	<h2>Launched</h2>
	@foreach($latestLaunchedMotions as $motion)
		<div>
			@if(isset($motion->motion_rank) && $motion->motion_rank>0)
				<img style="width:10%; padding-right:15px; padding-bottom:15px; float: left" src="<?=asset('/themes/'.config('app.themename').'/icons/arrow-top-right.png')?>">
			@else
				<img style="width:10%; padding-right:15px; padding-bottom:15px; float: left" src="<?=asset('/themes/'.config('app.themename').'/icons/arrow-bottom-right.png')?>">
			@endif
			<h4><a style="text-decoration: none; color:#1F1C22" href="<?=base_path('motions/'.$motion->id)?>">{{$motion->title}}</a></h4>
			<p>{{$motion->summary}}</p>
		</div>
	@endforeach
@endif

@if(!$recentlyClosedMotions->isEmpty())
	<h2>Closed</h2>
	@foreach($recentlyClosedMotions as $motion)
		<div>
			@if(isset($motion->motion_rank) && $motion->motion_rank>0)
				<img style="width:10%; padding-right:15px; padding-bottom:15px; float: left" src="<?=asset('/themes/'.config('app.themename').'/icons/arrow-top-right.png')?>">
			@else
				<img style="width:10%; padding-right:15px; padding-bottom:15px; float: left" src="<?=asset('/themes/'.config('app.themename').'/icons/arrow-bottom-right.png')?>">
			@endif
			<h4><a style="text-decoration: none; color:#1F1C22" href="<?=base_path('motions/'.$motion->id)?>">{{$motion->title}}</a></h4>
			<p>{{$motion->summary}}</p>
		</div>
	@endforeach
@endif


@endsection