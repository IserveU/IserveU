<?php

namespace App\Jobs\Setup;

use App\Setting;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SetDefaultSettings implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Setting::ifNotSetThenSet('motion', [
                'on'                                => true,
                'default_closing_time_delay'        => 120,
                'hours_before_closing_autoextend'   => 12,
                'hours_to_autoextend_by'            => 12,
                'minutes_between_rank_calculations' => 60,
                'email'                             => [
                    'admin' => true,
                    'users' => false,
                ],
                'allow_closing' => true,
            ]);


        Setting::ifNotSetThenSet('site', [
            'name'      => 'IserveU - eDemocracy',
            'terms'     => 'This system is built and maintained by volunteers, we can not be held liable for events beyond our reasonable  control. The software will be updated periodically to improve the user experience and performance. IserveU always endeavours to hand over care of the system to the government free of charge. In using this site you acknowledge that you are both a Canadian citizen and are an resident of Yellowknife who is eligible to vote in municipal elections.',
            'slogan'    => 'Conceived &amp; Forged In Yellowknife, Canada',
            'address'   => 'http://iserveu.ca',
            'twitter'   => 'http://twitter.com/iserveu_org',
            'facebook'  => 'https://www.facebook.com/iserveu.ca',
        ]);


        Setting::ifNotSetThenSet('voting', [
            'on'        => true,
            'abstain'   => true,
        ]);


        Setting::ifNotSetThenSet('comment', [
            'on'            => true,
            'cachetime'     => 60,
        ]);


        Setting::ifNotSetThenSet('security.login_attempts_lock', 5);
        Setting::ifNotSetThenSet('security.verify_citizens', true);

        Setting::ifNotSetThenSet('security.ask_for_birthday_on_create', false);

        //TODO: Language translation and should be hardcoded into translation files and maybe these could be over-rides?
        Setting::ifNotSetThenSet('jargon.en', [
                    'motion'      => 'Motion',
                    'motions'     => 'Motions',
                    'department'  => 'Department',
                    'departments' => 'Departments',
                ]);


        Setting::ifNotSetThenSet('home', [
                'widgets'       => [
                    'your_votes'    => true,
                    'your_comments' => true,
                    'top_comments'  => true,
                    'top_motions'   => true,
                ],
            ]);



        Setting::ifNotSetThenSet('theme', [
                'colors' => [
                    'primary'           => ['50'   => '61d3d8', '100'  => '61d3d8', '200'  => '61d3d8', '300'  => '61d3d8', '400'  => '00acb1',
                        '500'                      => '00acb1', '600'  => '00acb1', '700'  => '006e73', '800'  => '006e73', '900'  => '006e73', 'A100' => 'ff0000',
                        'A200'                     => 'ff0000', 'A400' => 'ff0000', 'A700' => 'ff0000', 'contrastDefaultColor' => 'light',
                    ],
                    'accent'            => ['50'   => 'ffb473', '100'  => 'ffb473', '200'  => 'ffb473', '300'  => 'ffb473', '400'  => 'ff7600',
                        '500'                      => 'ff7600', '600'  => 'ff7600', '700'  => 'a64d00', '800'  => 'a64d00', '900'  => 'a64d00', 'A100' => 'ffb473',
                        'A200'                     => 'ffb473', 'A400' => 'ffb473', 'A700' => 'a64d00', 'contrastDefaultColor' => 'light',
                    ],
                ],
                'name'  => 'default',
                'logo'          => '',
                'logo_mono'     => '',
                'symbol'        => '',
                'symbol_mono'   => '',
                'background'    => ''
            ]);



        Setting::ifNotSetThenSet('emails', [
            'welcome' => [
                'on'    => true,
                'text'  => "Welcome to the IserveU beta, IserveU is an open-source eDemocracy system built by volunteers in Yellowknife. We aim to upgrade our government and make it work better for everyone with more informed decision makers and more meaningful input from the public on decisions. \nWe welcome you to join in and vote on city issues during the beta process. When the system has proven it is reliable and accessible to Yellowknifers it will be used to make binding decisions in the Yellowknife city council, until then it operates as an advisory and feedback tool.",
                ],
        ]);


        Setting::save();
    }
}
