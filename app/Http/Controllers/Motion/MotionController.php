<?php

namespace App\Http\Controllers\Motion;

use App\Filters\MotionFilter;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Motion\DestroyMotionRequest;
use App\Http\Requests\Motion\IndexMotionRequest;
use App\Http\Requests\Motion\ShowMotionRequest;
use App\Http\Requests\Motion\StoreUpdateMotionRequest;
use App\Motion;
use Auth;
use Cache;

class MotionController extends ApiController
{
    public function __construct()
    {
        //Because this controller is partially visible
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource. If the user is logged in they will see the position they took on votes.
     *
     * @return Response
     */
    public function index(MotionFilter $filters, IndexMotionRequest $request)
    {
        $limit = $request->get('limit') ?: 20;

        return Cache::tags(['motion', 'motion.filters'])->rememberForever($filters->cacheKey($limit), function () use ($filters, $limit) {
            return Motion::filter($filters)->paginate($limit)->toJson();
        });
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(StoreUpdateMotionRequest $request)
    {
        $motion = Motion::create($request->all());

        return $motion;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show(Motion $motion, ShowMotionRequest $request)
    {
        return $motion;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function update(StoreUpdateMotionRequest $request, Motion $motion)
    {
        $motion->update($request->all());

        return $motion;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy(DestroyMotionRequest $request, Motion $motion)
    {
        $motion->delete(); //Partically voted motion kept in the database
        return $motion;
    }

    public function restore($id)
    {
        $motion = Motion::withTrashed()->with('user')->find($id);

        if (!$motion) {
            abort(404, 'Motion does not exist');
        }

        if ($motion->user->id != Auth::user()->id && !Auth::user()->can('delete-motion')) {
            abort(401, 'User does not have permission to restore and delete motions');
        }

        $motion->deleted_at = null; //restore() isn't working either
        $motion->status = 'closed'; //restore() isn't working either
        $motion->save();

        return $motion;
    }
}
