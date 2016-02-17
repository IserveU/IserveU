<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Setting;
use App\Http\Requests\StoreSetting;
use App\BackgroundImage;

class SettingController extends ApiController
{

  /**
     * SettingController middleware
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('setting.autosave'); 
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Setting::all();
    }

    /**
     * Creating the default settings.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Setting::set( $request->input('name'), $request->input('value') );

        return Setting::all();
    }

   
}
