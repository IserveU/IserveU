<?php

namespace App\Http\Controllers;

use App\Community;
use App\Http\Requests\Community\StoreCommunityRequest;
use App\Http\Requests\Community\UpdateCommunityRequest;
use Illuminate\Http\Request;

class CommunityController extends ApiController
{
    public function __construct()
    {
        $this->middleware('role:administrator', ['except' => ['index']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->input('limit') ?: 50;

        return Community::paginate($limit);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(StoreCommunityRequest $request)
    {
        $community = Community::create($request->all());

        return $community;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show(Community $community)
    {
        return $community;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function update(UpdateCommunityRequest $request, Community $community)
    {
        $community->update($request->all());

        return $community;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy(Community $community)
    {
        $community->delete();

        return $community;
    }
}
