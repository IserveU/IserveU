<?php

namespace App\Http\Controllers;

use App\Http\Requests\Setting\UpdateSettingRequest;
use App\Setting;

class SettingController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth', ['only' => 'update']);
        $this->middleware('role:administrator', ['only' => 'update']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response Returns a JSON of all the settings
     */
    public function index()
    {
        return Setting::all();
    }

    /**
     * Update a Value in the settings.
     *
     * @param \Illuminate\Http\Request $request The PUT data in JSON
     *                                          "key"   : leave empty if the setting you want
     *                                          to modify isn't in an array/object
     *
     *                                                "Value" : The new Value to give to your key
     * @param int $id Key of the setting
     *                to modify
     *
     * @return \Illuminate\Http\Response Returns a Json telling you if the
     *                                   changes were successful or not
     */
    public function update(UpdateSettingRequest $request, $id)
    {
        // big hack
        if (is_null($request->input('key'))) {
            $key = $id;
        } else {
            $key = $id.'.'.$request->input('key');
        }

        $value = $request->input('value');

        return Setting::update($key, $value) ?
            response()->json([
                    'message' => 'setting saved.',
                ], 200) :
            response()->json([
                'message' => 'key missing.',
            ], 400);
    }

    public function spa()
    {
        return \Theme::view('setting.directive');
    }

    /**
     * Manually flushes the site's cache.
     *
     * @return null
     */
    public function flushCache()
    {
        \Cache::flush();
    }
}
