@extends('email')

@section('content')

<p>Welcome to the IserveU beta {{$user->first_name}},</p>

<p>IserveU is an open-source eDemocracy system built by volunteers in Yellowknife. We aim to upgrade our government and make it work better for everyone with more informed decision makers and more meaningful input from the public on decisions.</p>

<p>We welcome you to join in and vote on city issues during the beta process. When the system has proven it is reliable and accessible to Yellowknifers it will be used to make binding decisions in the Yellowknife city council, until then it operates as an advisory and feedback tool.</p>

<p>Regards,<br/>
The IserveU Crew</p>

@endsection