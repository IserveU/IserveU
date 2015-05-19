@extends('app')

@section('content')
<div  class="darken" layout="row" layout-align="center center" fill-layout>
	<div flex-sm="100" flex-md="30" flex-lg="25" flex-gt-lg="20"  >

		<md-whiteframe  class="md-whiteframe-z5 loginbox" layout-padding layout-wrap layout="row" layout-align="center center">
				<div flex="25">
			
						<img  src="/img/logo_conference.png" />
				</div>
				
			<div flex="100">
				<form   method="POST" action="{{ url('/user/conferencelogin') }}" >

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
				      <input  name="email" type="email">
				    </md-input-container>

					<md-button type="submit" class="btn btn-primary">Login</md-button><md-button type="submit" class="btn btn-primary">Login Without Email</md-button>
					
				</form>
				</div>
		
		</md-whiteframe>
	</div>
</div>
	
@endsection
