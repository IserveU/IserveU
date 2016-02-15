<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Setting;
use App\Http\Requests\StoreSetting;
use App\BackgroundImage;
use Auth;
use DB;

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
        $settings = array(
            'motion'    =>   array(
                'default_closing_time_delay'        =>  120,    
                'hours_before_closing_autoextend'   =>  12,
                'hours_to_autoextend_by'            =>  12,
                'minutes_between_rank_calculations' =>  60
            ),
            'site'      =>   array(
                'name'      =>      'IserveU',
                'terms'     =>      'This system is built and maintained by volunteers, we can not be held liable for events beyond our reasonable control. The software will be updated periodically to improve the user experience and performance. IserveU always endeavours to hand over care of the system to the government free of charge. In using this site you acknowledge that you are both a Canadian citizen and are an resident of Yellowknife who is eligible to vote in municipal elections.'
            ),
            'module' => array(
                'motions'   => true,
                'voting'    => true,
                'comments'  => true
            ),
            'comment'  =>  array(
                'cachetime' =>  60
            ),
            'security'  =>  array(
                'login_attempts_lock'   =>  5
            ),
            // TODO: write a script to tie this into ngTranslate.
            'jargon'   => array(
                'en' => array(
                    'motion'  => 'Motion',
                    'motions' => 'Motions'
                ),
                'fr' => array(
                    'motion'  => 'Motion',
                    'motions' => 'Motions'
                )
            ),
            //TODO: make a job to seed these as ISU colors
            'theme' =>      array(
                'name'              => 'default',
                'logo'              => 'default',
                'favicon'           => 'default',
                'primary'           => array(
                    '50'   => '61d3d8',
                    '100'  => '61d3d8',
                    '200'  => '61d3d8',
                    '300'  => '61d3d8',
                    '400'  => '00acb1',
                    '500'  => '00acb1',
                    '600'  => '00acb1',
                    '700'  => '006e73',
                    '800'  => '006e73',
                    '900'  => '006e73',
                    'A100' => 'ff0000',
                    'A200' => 'ff0000',
                    'A400' => 'ff0000',
                    'A700' => 'ff0000',
                    'contrastDefaultColor' => 'light', 
                ),
                'accent'            => array(
                    '50'   => 'ffb473',
                    '100'  => 'ffb473',
                    '200'  => 'ffb473',
                    '300'  => 'ffb473',
                    '400'  => 'ff7600',
                    '500'  => 'ff7600',
                    '600'  => 'ff7600',
                    '700'  => 'a64d00',
                    '800'  => 'a64d00',
                    '900'  => 'a64d00',
                    'A100' => 'ffb473',
                    'A200' => 'ffb473',
                    'A400' => 'ffb473',
                    'A700' => 'a64d00',
                    'contrastDefaultColor' => 'light'
                ),
                'background_image'  => (new BackgroundImage)->today(),
            ),
            'home' =>  array(
                'introduction'  => '',
                'widgets'       => array(
                    'your_votes' => true,
                    'your_comments' => true,
                    'top_comments'  => true,
                    'top_motions'   => true
                )
            ),
            // depcrecated but being used on Javascript frontend, must switch to dot notation
            'themename' => 'default'
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
    public function store(Request $request)
    {
        Setting::set( $request->input('name'),$request->input('value') );

        return Setting::all();
    }

   
}
