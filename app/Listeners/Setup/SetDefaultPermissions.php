<?php

namespace App\Listeners\Setup;

use App\Events\Setup\Defaults;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Role;
use App\Permission;

class SetDefaultPermissions
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
        
        $admin = Role::updateOrCreate([
            'name'          =>  'administrator',
            'display_name'  =>  'Full Administrator',
            'description'   =>  'User is able to perform all database functions'
        ]);

        $citizen = Role::updateOrCreate([
            'name'          =>  'citizen',
            'display_name'  =>  'Citizen',
            'description'   =>  'A verified citizen'
        ]);

        $councillor = Role::updateOrCreate([
            'name'          =>  'councillor',
            'display_name'  =>  'City Councillor',
            'description'   =>  'A City Councillor'
        ]);        

        $editPermission = Permission::updateOrCreate([
            'name'          =>  'administrate-permissions',
            'display_name'  =>  'Administrate Permissions',
            'description'   =>  'Administrate the roles and permissions of other users'
        ]);

        $editUser =  Permission::updateOrCreate([
            'name'          =>  'administrate-users',
            'display_name'  =>  'Administrate Users',
            'description'   =>  'Administrate existing other users, verify them'
        ]);

        $showUser =  Permission::updateOrCreate([
            'name'          =>  'show-users',
            'display_name'  =>  'Show Users',
            'description'   =>  'See full existing (non-public) user profiles and their full details'
        ]);

        $deleteUser =  Permission::updateOrCreate([
            'name'          =>  'delete-users',
            'display_name'  =>  'Delete Users',
            'description'   =>  'Able to delete users'
        ]);
         

        $createMotion =  Permission::updateOrCreate([
            'name'          =>  'create-motions',
            'display_name'  =>  'Create Motion',
            'description'   =>  'Create and edit own motions'
        ]);

        $editMotion =  Permission::updateOrCreate([
            'name'          =>  'administrate-motions',
            'display_name'  =>  'Administrate Motion',
            'description'   =>  'Administrate existing motions, enable them'
        ]);

        $showMotion =  Permission::updateOrCreate([
            'name'          =>  'show-motions',
            'display_name'  =>  'Show Motion',
            'description'   =>  'Show all non-active motions'
        ]);

        $deleteMotion =  Permission::updateOrCreate([
            'name'          =>  'delete-motions',
            'display_name'  =>  'Delete Motion',
            'description'   =>  'Delete motions'
        ]);
        $createComment =  Permission::updateOrCreate([
            'name'          =>  'create-comments',
            'display_name'  =>  'Create Comment',
            'description'   =>  'Create and edit own comments'
        ]);

        $viewComment =  Permission::updateOrCreate([
            'name'          =>  'show-comments',
            'display_name'  =>  'Show Comment',
            'description'   =>  'View the comments and owners of comments that are not public'
        ]);

        $createCommentVote =  Permission::updateOrCreate([
            'name'          =>  'create-comment_votes',
            'display_name'  =>  'Create Comments',
            'description'   =>  'Can vote on comments'
        ]);

        $viewCommentVote =  Permission::updateOrCreate([
            'name'          =>  'view-comment_votes',
            'display_name'  =>  'Create Comments',
            'description'   =>  'Can view other users comment votes'
        ]);

        $deleteComment =  Permission::updateOrCreate([
            'name'          =>  'delete-comments',
            'display_name'  =>  'Delete Comment',
            'description'   =>  'Delete other peoples comments'
        ]);

        $createVote =  Permission::updateOrCreate([
            'name'          =>  'create-votes',
            'display_name'  =>  'Create Vote',
            'description'   =>  'Can vote and change own vote'
        ]);

        $showVote =  Permission::updateOrCreate([
            'name'          =>  'view-vote',
            'display_name'  =>  'View Vote',
            'description'   =>  'Can see who placed a vote and a detailed record of all votes cast (like most recent vote)'
        ]);

        $createBackgroundImage =  Permission::updateOrCreate([
            'name'          =>  'create-background_images',
            'display_name'  =>  'Create Background Image',
            'description'   =>  'Can create and upload a background image'
        ]);

        $editBackgroundImage =  Permission::updateOrCreate([
            'name'          =>  'administrate-background_images',
            'display_name'  =>  'Edit Background Image',
            'description'   =>  'Can activate and edit other background images'
        ]);

        $createDepartment =  Permission::updateOrCreate([
            'name'          =>  'create-department',
            'display_name'  =>  'Create Department',
            'description'   =>  'Can create a department'
        ]);

        $editDepartment =  Permission::updateOrCreate([
            'name'          =>  'administrate-department',
            'display_name'  =>  'Edit Department',
            'description'   =>  'Can activate and departments'
        ]);


        $councillor->perms()->sync(array($createComment->id,
                                             $createVote->id,
                                             $createMotion->id,
                                             $editMotion->id,
                                             $createCommentVote->id,
                                             $viewCommentVote->id));

        $citizen->perms()->sync(array($createBackgroundImage->id,
                                          $createComment->id,
                                          $createCommentVote->id,
                                          $createMotion->id,
                                          $createVote->id));

        $admin->perms()->sync(array($editUser->id,
                                        $showUser->id,
                                        $deleteUser->id,
                                        $createComment->id,
                                        $createVote->id,
                                        $createMotion->id,
                                        $editMotion->id,
                                        $showMotion->id,
                                        $deleteMotion->id,
                                        $createProperty->id,
                                        $editProperty->id,
                                        $viewComment->id,
                                        $showVote->id,
                                        $createCommentVote->id,
                                        $viewCommentVote->id,
                                        $editPermission->id,
                                        $createBackgroundImage->id,
                                        $editBackgroundImage->id,
                                        $createDepartment->id,
                                        $editDepartment->id));    
    }
}
