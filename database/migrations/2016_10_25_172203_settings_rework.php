<?php

use App\Setting;
use Illuminate\Database\Migrations\Migration;

class SettingsRework extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Setting::renameSetting('email.footer.slogan', 'site.slogan');
        Setting::renameSetting('email.footer.address', 'site.address');
        Setting::renameSetting('email.footer.twitter', 'site.twitter');
        Setting::renameSetting('email.footer.facebook', 'site.facebook');
        Setting::renameSetting('email.welcome', 'emails.welcome.text');
        Setting::renameSetting('abstain', 'voting.abstain');
        Setting::renameSetting('allow_closing', 'motion.allow_closing');
        Setting::renameSetting('themename', 'theme.name');
        Setting::forget('security.verify_citizens');

        Setting::forget('email');
        Setting::forget('module');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
