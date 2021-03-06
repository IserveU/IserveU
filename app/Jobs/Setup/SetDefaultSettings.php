<?php

namespace App\Jobs\Setup;

use App\Setting;
use App\User;

class SetDefaultSettings
{
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
                'on'                                => 1,
                'default_closing_time_delay'        => 120,
                'hours_before_closing_autoextend'   => 12,
                'hours_to_autoextend_by'            => 12,
                'minutes_between_rank_calculations' => 60,
                'allow_closing'                     => 1,
            ]);

        Setting::ifNotSetThenSet('site', [
            'name'  => 'IserveU - eDemocracy',
            'terms' => [
                'force' => 1,
                'text'  => 'This system is built and maintained by volunteers, we can not be held liable for events beyond our reasonable  control. The software will be updated periodically to improve the user experience and performance.',
            ],
            'slogan'       => 'Conceived &amp; Forged In Yellowknife, Canada',
            'address'      => 'http://iserveu.ca',
            'twitter'      => 'http://twitter.com/iserveu_org',
            'facebook'     => 'https://www.facebook.com/iserveu.ca',
            'backup'       => '0 0 * * *',
            'analytics_id' => '', //UA-00000000-0
        ]);

        Setting::ifNotSetThenSet('voting', [
            'on'      => 1,
            'abstain' => 1,
        ]);

        Setting::ifNotSetThenSet('comment', [
            'on'        => 1,
            'cachetime' => 60,
        ]);

        Setting::ifNotSetThenSet('authentication', [
            'login_attempts_lock'        => 5,
            'ask_for_birthday_on_create' => 1,
            'required'                   => 1,
        ]);

        //TODO: Language/term translation and should be hardcoded into translation files and maybe these could be over-rides?
        Setting::ifNotSetThenSet('jargon.en', [
                    'motion'      => 'Motion',
                    'motions'     => 'Motions',
                    'department'  => 'Department',
                    'departments' => 'Departments',
                ]);

        Setting::ifNotSetThenSet('home', [
                'widgets' => [
                    'your_votes'    => 1,
                    'your_comments' => 1,
                    'top_comments'  => 1,
                    'top_motions'   => 1,
                ],
            ]);

        Setting::ifNotSetThenSet('theme', [
                'customTheme' => 1,
                'predefined'  => [
                    'primary' => 'purple',
                    'accent'  => 'green',
                ],
                'colors' => [
                    'primary' => ['50' => '61d3d8', '100' => '61d3d8', '200' => '61d3d8', '300' => '61d3d8', '400' => '00acb1',
                        '500'          => '00acb1', '600' => '00acb1', '700' => '006e73', '800' => '006e73', '900' => '006e73', 'A100' => 'ff0000',
                        'A200'         => 'ff0000', 'A400' => 'ff0000', 'A700' => 'ff0000', 'contrastDefaultColor' => 'light',
                    ],
                    'accent' => ['50' => 'ffb473', '100' => 'ffb473', '200' => 'ffb473', '300' => 'ffb473', '400' => 'ff7600',
                        '500'         => 'ff7600', '600' => 'ff7600', '700' => 'a64d00', '800' => 'a64d00', '900' => 'a64d00', 'A100' => 'ffb473',
                        'A200'        => 'ffb473', 'A400' => 'ffb473', 'A700' => 'a64d00', 'contrastDefaultColor' => 'light',
                    ],
                ],
                'name'        => 'default',
                'logo'        => '',
                'logo_mono'   => '',
                'symbol'      => '',
                'symbol_mono' => '',
                'background'  => '',
            ]);

        Setting::ifNotSetThenSet('emails', [
            'welcome' => [
                'on'   => 1,
                'text' => 'Welcome to this deployment of IserveU, IserveU is an open-source eDemocracy system built by volunteers in Yellowknife. We aim to upgrade our government and make it work better for everyone with more informed decision makers and more meaningful input from the public on decisions.',
            ],
        ]);
        Setting::ifNotSetThenSet('betaMessage', [
                    'on'   => 1,
                    'text' => 'This software is currently in BETA. Features and improvements are constantly being added. If you would like give feedback visit our website',
                ]);
        Setting::save();
    }
}
