<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;
use App\PropertyPollDivision;
use Auth;
use DB;

class PropertyPollDivisionController extends ApiController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		if(!Auth::user()->can('administrate-properties')){ //Logged in user will want to see if they voted on these things
			abort(401,'You do not have permission to see property assessments');
		}

		$limit = Request::get('limit') ?: 50;

		return $propertypolldivision = PropertyPollDivision::simplePaginate($limit);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		if(!Auth::user()->can('administrate-properties')){ //Logged in user will want to see if they voted on these things
			abort(401,'You do not have permission to see property assessments');
		}

		$limit = Request::get('limit') ?: 50;

		return $propertypolldivision = PropertyPollDivision::simplePaginate($limit);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		if(!Auth::user()->can('create-properties')){
			abort(401,'You do not have permission to create a property assessment');
		}

		$propertypolldivision = (new PropertyPollDivision)->secureFill(Request::all()); 
		if(!$propertypolldivision->save()){
		 	abort(403,$propertypolldivision->errors);
		}
     	return $propertypolldivision;

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(PropertyPollDivision $propertypolldivision)
	{
		return $propertypolldivision;
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(PropertyPollDivision $propertypolldivision)
	{
		if(!Auth::user()->can('create-properties')){
			abort(403,'You do not have permission to create/update propert yassessment');
		}

		if(!Auth::user()->can('administrate-properties')){ //Is not the user who made it, or the site admin
			abort(401,"This user can not administrate this property assessment ($id)");
		}

		return $propertypolldivision->fields;
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(PropertyPollDivision $propertypolldivision)
	{
		if(!Auth::user()->can('create-properties')){
			abort(403,'You do not have permission to update a property assessment');
		}

		if(!Auth::user()->can('administrate-properties')){ //Is not the user who made it, or the site admin
			abort(401,"This user can not edit property assessment ($id)");
		}

		$propertypolldivision->secureFill(Request::all());

		if(!$propertypolldivision->save()){
		 	abort(403,$propertypolldivision->errors);
		}

		$propertypolldivision->save();
		
		return $propertypolldivision;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(PropertyPollDivision $propertypolldivision)
	{
		//
	}

}
