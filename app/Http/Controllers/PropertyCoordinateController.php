<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;
use App\PropertyCoordinate;
use Auth;
use DB;

class PropertyCoordinateController extends ApiController {

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

		return $propertycoordinate = PropertyCoordinate::simplePaginate($limit);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		if(!Auth::user()->can('create-properties')){
			abort(401,'You do not have permission to create a property assessment');
		}

		return (new PropertyCoordinate)->fields;		
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

		$propertycoordinate = (new PropertyCoordinate)->secureFill(Request::all()); 
		if(!$propertycoordinate->save()){
		 	abort(403,$propertycoordinate->errors);
		}
     	return $propertycoordinate;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(PropertyCoordinate $propertycoordinate)
	{
		return $propertycoordinate;
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(PropertyCoordinate $propertycoordinate)
	{
		if(!Auth::user()->can('create-properties')){
			abort(403,'You do not have permission to create/update propert yassessment');
		}

		if(!Auth::user()->can('administrate-properties')){ //Is not the user who made it, or the site admin
			abort(401,"This user can not administrate this property assessment ($id)");
		}

		return $propertycoordinate->fields;

	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(PropertyCoordinate $propertycoordinate)
	{
		if(!Auth::user()->can('create-properties')){
			abort(403,'You do not have permission to update a property assessment');
		}

		if(!Auth::user()->can('administrate-properties')){ //Is not the user who made it, or the site admin
			abort(401,"This user can not edit property assessment ($id)");
		}

		$propertycoordinate->secureFill(Request::all());

		if(!$propertycoordinate->save()){
		 	abort(403,$propertycoordinate->errors);
		}

		$propertycoordinate->save();
		
		return $propertycoordinate;

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(PropertyCoordinate $propertycoordinate)
	{
		//
	}

}
