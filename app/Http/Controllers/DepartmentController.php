<?php

namespace App\Http\Controllers;

// use App\Http\Requests;
// use App\Http\Controllers\Controller;

// use Illuminate\Http\Request;

use App\Department;
use Auth;
use Illuminate\Support\Facades\Request;

class DepartmentController extends ApiController
{
    protected $departmentTransformer;

    public function __construct()
    {
        $this->middleware('role:administrator', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return Department::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        if (!Auth::user()->can('create-department')) {
            abort(401, 'You do not have permission to create a department');
        }

        $department = (new Department())->secureFill(Request::all()); //Does the fields specified as fillable in the model


        if (!$department->save()) {
            abort(403, $department->errors);
        }

        return $department;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function update(Department $department)
    {
        if (!Auth::user()->can('create-department')) {
            abort(403, 'You do not have permission to update a department');
        }

        if (!$department->user_id != Auth::user()->id && !Auth::user()->can('administrate-department')) { //Is not the user who made it, or the site admin
            abort(401, "This user can not edit department ($id)");
        }
        $department->secureFill(Request::all());

        if (!$department->save()) {
            abort(403, $department->errors);
        }

        return $department;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy(Department $department)
    {
        if (!$department) {
            abort(403, 'Department does not exist, permanently deleted');
        }
        if (!Auth::user()->can('administrate-department')) {
            abort(401, 'You do not have permission to delete this department');
        }

        $department->delete();

        return $department;
    }
}
