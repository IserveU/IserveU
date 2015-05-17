@extends('app')

@section('content')
<div style="height:100%" class="darken" layout="row" layout-align="center center" fill-layout>
	<div flex-sm="100" flex-md="30" flex-lg="25" flex-gt-lg="20"  >
		<div class="loginlogo"  layout-padding >
				<img src="/img/logo_conference.png" />
		</div>
		<md-whiteframe  class="md-whiteframe-z5 loginbox" layout-padding  layout="row" >
			
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

			<form flex="100"  method="POST" action="{{ url('/user/conferencelogin') }}" layout="column">

				<input type="hidden" name="_token" value="{{ csrf_token() }}">

				<md-input-container flex>
					<label>First Name</label>
			      	<input  required name="first_name">
				
			    </md-input-container>

			    <md-input-container flex>
			      <label>Last Name</label>
			      <input required name="last_name">
			    </md-input-container>
				
				

		 		<md-input-container flex>
			      <label>Email</label>
			      <input required name="email" type="email">
			    </md-input-container>

				<md-button type="submit" class="btn btn-primary">Login</md-button>
				
			</form>
		
		</md-whiteframe>
	</div>
</div>
	
@endsection
