@extends('app')

@section('content')

	<md-whiteframe class="md-whiteframe-z5" layout-padding>
		@if (count($errors) > 0)
			<div class="alert alert-danger">
				<strong>Whoops!</strong> There were some problems with your input.<br><br>
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif

		<form  method="POST" action="{{ url('/user/conferencelogin') }}">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">


		
			<md-input-container>
		      <label>First Name</label>
		      <input name="first_name">
		    </md-input-container>

		    <md-input-container>
		      <label>Last Name</label>
		      <input name="last_name">
		    </md-input-container>
			
			

	 		<md-input-container>
		      <label>Email</label>
		      <input name="email" type="email">
		    </md-input-container>

			
					<md-button type="submit" class="btn btn-primary">Login</button>
				
			
		</form>
	</md-whiteframe>
@endsection
