<?php

use App\User;
use App\Repositories\Preferences\PreferenceMananger;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenamePreferences extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $users = User::all();

        foreach($users as $user){
          $preferenceManager = new PreferenceMananger($user);

          $preferenceManager->renamePreferences(
                              [
                                'authentication.notify.admin.oncreate'     => 'authentication.notify.admin.oncreate.on',
                                'authentication.notify.admin.summary'      => 'authentication.notify.admin.summary.on',
                                'authentication.notify.user.onrolechange'  => 'authentication.notify.user.onrolechange.on',

                                'motion.notify.user.onchange'              => 'motion.notify.user.onchange.on',
                                'motion.notify.user.summary'               => 'motion.notify.user.summary.on',
                                'motion.notify.admin.summary'              => 'motion.notify.admin.summary.on',
                              ]
                            )
                            ->setDefaults()
                            ->save();


        }
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
