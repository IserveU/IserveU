<?php namespace App\Http\Controllers;

// use App\Http\Requests;
// use App\Http\Controllers\Controller;

// use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use App\Department;
use App\Motion;
use Auth;
use DB;
use Cache;

use Validator;


class DepartmentController extends ApiController {
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// if (Auth::user()->can('create-motions')) { //An admin able to see all users
		// 	$departments = Department::all();
		// 	return $departments;
		// }

		//Other people can see a list of the public users
		$departments = Department::all();
		return $departments;
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		if(!Auth::user()->can('create-department')){
			abort(401,'You do not have permission to create a department');
		}
		return (new Department)->rules();

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		if(!Auth::user()->can('create-department')){
			abort(401,'You do not have permission to create a department');
		}

		$department = (new Department)->secureFill(Request::all()); //Does the fields specified as fillable in the model
		

		if(!$department->save()){
		 	abort(403,$department->errors);
		}

		return $department;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Department $department)
	{
		if(!Auth::user()->can('create-department')){
			abort(403,'You do not have permission to create/update departments');
		}

		if(!$department->user_id!=Auth::user()->id && !Auth::user()->can('administrate-department')){ //Is not the user who made it, or the site admin
			abort(401,"This user can not edit department ($id)");
		}

		return $department->fields;
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Department $department)
	{
		if(!Auth::user()->can('create-department')){
			abort(403,'You do not have permission to update a department');
		}

		if(!$department->user_id!=Auth::user()->id && !Auth::user()->can('administrate-department')){ //Is not the user who made it, or the site admin
			abort(401,"This user can not edit department ($id)");
		}
		$department->secureFill(Request::all());
		
		if(!$department->save()){
		 	abort(403,$department->errors);
		}

		return $department;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Department $department)
	{
		if(!$department){
			abort(403,"Department does not exist, permanently deleted");
		}
		if(!Auth::user()->can('administrate-department')){
			abort(401,"You do not have permission to delete this department");
		}
		
		$department->delete();

		return $department;
	}

}
