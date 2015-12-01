<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Setting;
use App\Http\Requests\StoreSetting;

class SettingController extends ApiController
{

  /**
     * SettingController middleware
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('role:administrator'); 
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
     * Creating the default settings
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $settings = array(
            'motion'    =>   array(
                'default_closing_time_delay'        =>  120,    
                'hours_before_closing_autoextend'   =>  12,
                'hours_to_autoextend_by'            =>  12,
                'minutes_between_rank_calculations' =>  60
            ),
            'site'      =>   array(
                'name'      =>      'IserveU Site',
                'terms'     =>      'This system is built and maintained by volunteers, we can not be held liable for events beyond our reasonable control. The software will be updated periodically to improve the user experience and performance. IserveU always endeavours to hand over care of the system to the government free of charge. In using this site you acknowledge that you are both a Canadian citizen and are an resident of Yellowknife who is eligible to vote in municipal elections.'
            ),
            'comment'  =>  array(
                'cachetime' =>  60
            ),
            'security'  =>  array(
                'login_attempts_lock'   =>  5
            ),
            'theme' =>      array(
                'name'      =>  'default'
            )
        );

        foreach($settings as $name => $content){
            if(!Setting::get($name)){
                Setting::set($name,$content);
            }
        }

       return json_encode(Setting::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSetting $request)
    {
        Setting::set($request->input('name'),$request->input('value'));
    }

   
}
