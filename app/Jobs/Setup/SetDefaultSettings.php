<?php

namespace App\Jobs\Setup;

use App\Setting;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
                'email'                             => [
                    'admin' => 1,
                    'users' => 0,
                ],
                'allow_closing' => 1,
            ]);

        //TODO: The logo should be copied during account creation to a storage route and that reference kept under theme.logo
        Setting::ifNotSetThenSet('login', [
                'logo' => '/themes/default/logo/logo.png',
            ]);

        //TODO: The logo should be copied during account creation to a storage route and that reference kept under theme.icon
        Setting::ifNotSetThenSet('favicon', '/themes/default/logo/symbol.png');



        Setting::ifNotSetThenSet('site', [
            'name'      => 'IserveU - eDemocracy',
            'terms'     => 'This system is built and maintained by volunteers, we can not be held liable for events beyond our reasonable  control. The software will be updated periodically to improve the user experience and performance. IserveU always endeavours to hand over care of the system to the government free of charge. In using this site you acknowledge that you are both a Canadian citizen and are an resident of Yellowknife who is eligible to vote in municipal elections.',
            'slogan'    => 'Conceived &amp; Forged In Yellowknife, Canada',
            'address'   => 'http://iserveu.ca',
            'twitter'   => 'http://twitter.com/iserveu_org',
            'facebook'  => 'https://www.facebook.com/iserveu.ca',
        ]);


        Setting::ifNotSetThenSet('voting', [
            'on'        => 1,
            'abstain'   => 1,
        ]);


        Setting::ifNotSetThenSet('comment', [
            'on'            => 1,
            'cachetime'     => 60,
        ]);


        Setting::ifNotSetThenSet('security.login_attempts_lock', 5);
        Setting::ifNotSetThenSet('security.verify_citizens', 1);

        Setting::ifNotSetThenSet('security.ask_for_birthday_on_create', 0);

        //TODO: Language translation isn't jargon and should be hardcoded into translation files
        Setting::ifNotSetThenSet('jargon.en', [
                    'motion'      => 'Motion',
                    'motions'     => 'Motions',
                    'department'  => 'Department',
                    'departments' => 'Departments',
                ]);

        //TODO: Seems like duplication, what one is real?
        Setting::ifNotSetThenSet('logo', 'default');

        //TODO: Language translation isn't jargon and should be hardcoded into translation files
        Setting::ifNotSetThenSet('jargon.fr', [
                    'motion'      => 'Motion',
                    'motions'     => 'Motions',
                    'department'  => 'Département',
                    'departments' => 'Départements',
                ]);

        //TODO: If this is seed data it needs to go into pages or the DB seeder for pages
        Setting::ifNotSetThenSet('home', [
                'introduction'  => [
                    'icon'  => '/themes/default/logo/symbol_onlight.svg',
                    'title' => 'Introduction to IserveU',
                    'text'  => '<p><b id="docs-internal-guid-c9f5e80b-5721-1451-1648-7ed3572866d5"></b></p><p dir="ltr"><b id="docs-internal-guid-6f029f4d-5e0f-0284-f889-d9344bed5ab4"></b></p><p dir="ltr"><span style="color: rgb(0, 0, 0);background-color: transparent;">Welcome to IserveU, the world-leading E-Democracy and public engagement tool for your city council.</span><br></p><p dir="ltr"><span style="color: #000000;background-color: transparent;">We’re excited to provide this open-source software to let you vote on, engage with, and influence decisions about issues you find important. &nbsp;</span></p><p dir="ltr"><span style="color: #000000;background-color: transparent;">We’re proud to be made by and for Yellowknifers with a mission to give our friends and neighbours the easiest, most cost-effective way to engage with government decision-making in a tangible, quantifiable way between elections. </span></p><p dir="ltr"><span style="color: #000000;background-color: transparent;">Vote on issues, start conversations, and engage with elected representatives using the tool in real-time; all without attending a council meeting. </span></p><p dir="ltr"><span style="background-color: transparent;"><font color="#000000">Because it’s time to leverage technology for a stronger, more transparent democracy. Because your opinion matters.<br></font></span></p><p dir="ltr"><span style="background-color: transparent;"><font color="#000000"><br></font></span></p><h4><span style="background-color: transparent;"><font color="#000000">How Does IserveU Work?</font></span></h4><hr><p dir="ltr"><iframe width="560" height="315" src="https://www.youtube.com/embed/8sq7ydOCyJs" style="text-align: center;"></iframe><br></p>',
                ],
                'widgets'       => [
                    'your_votes'    => 1,
                    'your_comments' => 1,
                    'top_comments'  => 1,
                    'top_motions'   => 1,
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
            ]);



        //TODO: Some of this might belong in email templates, which will work with multi tenancy although this seems like a good spot for now
        Setting::ifNotSetThenSet('emails', [
            'welcome' => [
                'on'    => 1,
                'text'  => "Welcome to the IserveU beta, IserveU is an open-source eDemocracy system built by volunteers in Yellowknife. We aim to upgrade our government and make it work better for everyone with more informed decision makers and more meaningful input from the public on decisions. \nWe welcome you to join in and vote on city issues during the beta process. When the system has proven it is reliable and accessible to Yellowknifers it will be used to make binding decisions in the Yellowknife city council, until then it operates as an advisory and feedback tool.",
                ],
        ]);


        Setting::save();
    }
}
