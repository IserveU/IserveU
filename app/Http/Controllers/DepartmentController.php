<?php

namespace App\Http\Controllers;

use App\Department;
use App\Http\Requests\Department\StoreDepartmentRequest;
use App\Http\Requests\Department\UpdateDepartmentRequest;
use Auth;
use Illuminate\Http\Request;

class DepartmentController extends ApiController
{
    public function __construct()
    {
        $this->middleware('role:administrator', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $limit = $request->input('limit') ?: 50;

        return Department::paginate($limit);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(StoreDepartmentRequest $request)
    {
        $department = Department::create($request->all());

        return $department;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show(Department $department)
    {
        return $department;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function update(UpdateDepartmentRequest $request, Department $department)
    {
        $department->update($request->all());

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
