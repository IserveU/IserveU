<?php

namespace App\Listeners\Setup;

use App\Events\Setup\Defaults;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\BackgroundImage;

use Setting;

class SetDefaultSettings
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Defaults  $event
     * @return void
     */
    public function handle($event)
    {
        $this->ifNotSetThenSet('motion', array(
                'default_closing_time_delay'        =>  120,    
                'hours_before_closing_autoextend'   =>  12,
                'hours_to_autoextend_by'            =>  12,
                'minutes_between_rank_calculations' =>  60
            ));

        $this->ifNotSetThenSet('login', array(
                'logo' => '/themes/default/logo/logo.png'
            ));

        $this->ifNotSetThenSet('site', array(
                'name'      =>      'IserveU',
                'terms'     =>      'This system is built and maintained by volunteers, we can not be held liable for events beyond our reasonable  control. The software will be updated periodically to improve the user experience and performance. IserveU always endeavours to hand over care of the system to the government free of charge. In using this site you acknowledge that you are both a Canadian citizen and are an resident of Yellowknife who is eligible to vote in municipal elections.'
            ));

        $this->ifNotSetThenSet('module', array(
                'motions'   => true,
                'voting'    => true,
                'comments'  => true
            ));

        $this->ifNotSetThenSet('comment', array(
                'cachetime' => 60
            ));

        $this->ifNotSetThenSet('security', array(
                'login_attempts_lock' => 5
            ));

        $this->ifNotSetThenSet('abstain', true);

        $this->ifNotSetThenSet('jargon', array(
                'en' => array(
                    'motion'  => 'Motion',
                    'motions' => 'Motions',
                    'department' => 'Department',
                    'departments' => 'Departments'
                ),
                'fr' => array(
                    'motion'  => 'Motion',
                    'motions' => 'Motions',
                    'department' => 'Département',
                    'departments' => 'Départements'
                )
            ));

        $this->ifNotSetThenSet('home', array(
                'introduction'  => array(
                    'icon' => '/themes/default/logo/symbol_onlight.svg',
                    'title' => 'Welcome to IserveU!',
                    'text' => "We’re excited to provide this open-source software to let you vote on, engage with, and influence decisions about issues you find important.\n\n As the world's leading E-Democracy and public engagement tool, we're proud to be made by and for Yellowknifers with a mission to give our friends and neighbours the easiest, most cost-effective way to engage with government decision-making between elections.\n\n Vote on issues, start conversations, and engage with elected representatives using the tool in real-time; all without attending a council meeting.\n\n Because it’s time to leverage technology for a stronger, more transparent democracy. Because your opinion matters."
                ),
                'widgets'       => array(
                    'your_votes' => true,
                    'your_comments' => true,
                    'top_comments'  => true,
                    'top_motions'   => true
                )
            ));

        $this->ifNotSetThenSet('themename', 'default');

        $this->ifNotSetThenSet('theme', array(
                'primary'           => array('50'   => '61d3d8', '100'  => '61d3d8', '200'  => '61d3d8','300'  => '61d3d8', '400'  => '00acb1',
                    '500'  => '00acb1', '600'  => '00acb1', '700'  => '006e73', '800'  => '006e73', '900'  => '006e73', 'A100' => 'ff0000',
                    'A200' => 'ff0000', 'A400' => 'ff0000', 'A700' => 'ff0000', 'contrastDefaultColor' => 'light'
                ),
                'accent'            => array('50'   => 'ffb473', '100'  => 'ffb473', '200'  => 'ffb473', '300'  => 'ffb473', '400'  => 'ff7600', 
                    '500'  => 'ff7600', '600'  => 'ff7600', '700'  => 'a64d00', '800'  => 'a64d00', '900'  => 'a64d00', 'A100' => 'ffb473', 
                    'A200' => 'ffb473', 'A400' => 'ffb473', 'A700' => 'a64d00', 'contrastDefaultColor' => 'light'
                )
            ));

        $this->ifNotSetThenSet('background_image', (new BackgroundImage)->today());

        $this->ifNotSetThenSet('logo', 'default');

        $this->ifNotSetThenSet('allow_closing', true);

        $this->ifNotSetThenSet('email', array(
                'footer' => array('slogan' => 'Conceived &amp; Forged In Yellowknife, Canada',
                                  'website' => 'http://iserveu.ca',
                                  'twitter' => 'http://twitter.com/iserveu_org',
                                  'facebook' => 'https://www.facebook.com/iserveu.ca'),
                'welcome' => "<p>Welcome to the IserveU beta,</p><p>IserveU is an open-source eDemocracy system built by volunteers in Yellowknife. We aim to upgrade our government and make it work better for everyone with more informed decision makers and more meaningful input from the public on decisions.</p><p>We welcome you to join in and vote on city issues during the beta process. When the system has proven it is reliable and accessible to Yellowknifers it will be used to make binding decisions in the Yellowknife city council, until then it operates as an advisory and feedback tool.</p>\n\n<p>Regards,<br/>The IserveU Crew</p>"
            ));

            Setting::save();

    }


    public function ifNotSetThenSet($key,$value){
        if(is_null(\Setting::get($key))){
            \Setting::set($key,$value);
        }

    }
}
