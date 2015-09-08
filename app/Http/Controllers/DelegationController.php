<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Motion;
use App\Delegation;
use App\Department;
use DB;


class DelegationController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

    //    $delegationTotals = DB::table('delegations')->select('delegate_to_id', DB::raw('count(*) as total'))->groupBy('delegate_to_id')->orderBy('total','ASC')->get();

        DB::enableQueryLog();

        $validUsers = User::notCouncillor()->get();
        $departments = Department::all();

        $numberOfCouncilors = User::councillor()->count();

        if($numberOfCouncilors){
            foreach($validUsers as $user){
                foreach($departments as $department){
                    $councillors = User::councillor()->get();
                    $leastDelegatedToCouncillor = $councillors->sortBy('totalDelegationsTo')->first();
                    $newDelegation = new Delegation;
                    $newDelegation->department_id       =   $department->id;
                    $newDelegation->delegate_from_id    =   $user->id;
                    $newDelegation->delegate_to_id      =   $leastDelegatedToCouncillor->id;
                    $newDelegation->save();
                }
            }
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
