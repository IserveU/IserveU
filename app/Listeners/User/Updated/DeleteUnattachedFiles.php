<?php

namespace App\Listeners\User\Updated;

use App\Events\User\UserUpdated;
use App\File;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeleteUnattachedFiles implements ShouldQueue
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
     * @param UserUpdated $event
     *
     * @return void
     */
    public function handle(UserUpdated $event)
    {
        $user = $event->user;
        $dirty = $user->getDirty();
        $original = $user->getOriginal();

        if (array_key_exists('government_identification_id', $dirty)) {
            $file = File::find($original['government_identification_id']);
            if ($file) {
                $file->delete();
            }
        }

        if (array_key_exists('avatar_id', $dirty)) {
            $file = File::find($original['avatar_id']);
            if ($file) {
                $file->delete();
            }
        }
    }
}
