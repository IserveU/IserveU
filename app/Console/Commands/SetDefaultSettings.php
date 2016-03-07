<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\BackgroundImage;
use Setting;


class SetDefaultSettings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settings:default {--o|overwrite}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sets default settings in the app without changing existing settings. 
                                         {--overwrite} will reset all settings to default.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $overwrite = $this->option('overwrite');

        $this->ifNullSet('motion', array(
                'default_closing_time_delay'        =>  120,    
                'hours_before_closing_autoextend'   =>  12,
                'hours_to_autoextend_by'            =>  12,
                'minutes_between_rank_calculations' =>  60
            ), $overwrite);

        $this->ifNullSet('login', array(
                'logo' => '/themes/default/logo/logo.png'
            ), $overwrite);

        $this->ifNullSet('site', array(
                'name'      =>      'IserveU',
                'terms'     =>      'This system is built and maintained by volunteers, we can not be held liable for events beyond our reasonable  control. The software will be updated periodically to improve the user experience and performance. IserveU always endeavours to hand over care of the system to the government free of charge. In using this site you acknowledge that you are both a Canadian citizen and are an resident of Yellowknife who is eligible to vote in municipal elections.'
            ), $overwrite);

        $this->ifNullSet('module', array(
                'motions'   => true,
                'voting'    => true,
                'comments'  => true
            ), $overwrite);

        $this->ifNullSet('comment', array(
                'cachetime' => 60
            ), $overwrite);

        $this->ifNullSet('security', array(
                'login_attempts_lock' => 5
            ), $overwrite);

        $this->ifNullSet('abstain', true, $overwrite);

        $this->ifNullSet('jargon', array(
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
            ), $overwrite);

        $this->ifNullSet('home', array(
                'introduction'  => array(
                    'icon' => '/themes/default/logo/symbol_onlight.svg',
                    'title' => 'Welcome to Iserveu',
                    'text' => "This is brand new eDemocracy platform software. It's super easy to get started."
                ),
                'widgets'       => array(
                    'your_votes' => true,
                    'your_comments' => true,
                    'top_comments'  => true,
                    'top_motions'   => true
                )
            ), $overwrite);

        $this->ifNullSet('themename', 'default', $overwrite);

        $this->ifNullSet('theme', array(
                'primary'           => array('50'   => '61d3d8', '100'  => '61d3d8', '200'  => '61d3d8','300'  => '61d3d8', '400'  => '00acb1',
                    '500'  => '00acb1', '600'  => '00acb1', '700'  => '006e73', '800'  => '006e73', '900'  => '006e73', 'A100' => 'ff0000',
                    'A200' => 'ff0000', 'A400' => 'ff0000', 'A700' => 'ff0000', 'contrastDefaultColor' => 'light'
                ),
                'accent'            => array('50'   => 'ffb473', '100'  => 'ffb473', '200'  => 'ffb473', '300'  => 'ffb473', '400'  => 'ff7600', 
                    '500'  => 'ff7600', '600'  => 'ff7600', '700'  => 'a64d00', '800'  => 'a64d00', '900'  => 'a64d00', 'A100' => 'ffb473', 
                    'A200' => 'ffb473', 'A400' => 'ffb473', 'A700' => 'a64d00', 'contrastDefaultColor' => 'light'
                )
            ), $overwrite);

        $this->ifNullSet('background_image', (new BackgroundImage)->today(), $overwrite);

        $this->ifNullSet('logo', 'default', $overwrite);

        Setting::save();
    }

    public function ifNullSet($key, $value, $overwrite)
    {
        if ($overwrite) {
            Setting::set($key,$value);
        } else if(is_null(Setting::get($key))) {
            Setting::set($key,$value);
        }
    }

}
