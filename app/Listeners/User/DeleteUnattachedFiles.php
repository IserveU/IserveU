<?php

namespace App\Listeners\User;

use App\Events\UserUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\File;

class DeleteUnattachedFiles
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
     * @param  UserUpdated  $event
     * @return void
     */
    public function handle(UserUpdated $event)
    {
        $user       = $event->user;
        $dirty      = $user->getDirty();
        $original   = $user->getOriginal();

        if(array_key_exists('government_identification_id',$dirty)){
            $file = File::find($original['government_identification_id']);
            if($file){
                $file->delete();
            }
            
        }

        if(array_key_exists('avatar_id',$dirty)){
            $file = File::find($original['avatar_id']);
            if($file){
                $file->delete();
            }
        }
    }
}
