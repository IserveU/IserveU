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

        $representative = Role::updateOrCreate([
            'name'          =>  'representative',
            'display_name'  =>  'Representative',
            'description'   =>  'A representative who by default is deffered votes'
        ]);        

        $editPermission = Permission::updateOrCreate([
            'name'          =>  'administrate-permission',
            'display_name'  =>  'Administrate Permissions',
            'description'   =>  'Administrate the roles and permissions of other users'
        ]);

        $editUser =  Permission::updateOrCreate([
            'name'          =>  'administrate-user',
            'display_name'  =>  'Administrate Users',
            'description'   =>  'Administrate existing other users, verify them'
        ]);

        $showUser =  Permission::updateOrCreate([
            'name'          =>  'show-user',
            'display_name'  =>  'Show Users',
            'description'   =>  'See full existing (non-public) user profiles and their full details'
        ]);

        $deleteUser =  Permission::updateOrCreate([
            'name'          =>  'delete-user',
            'display_name'  =>  'Delete Users',
            'description'   =>  'Able to delete users'
        ]);

        $createMotion =  Permission::updateOrCreate([
            'name'          =>  'create-motion',
            'display_name'  =>  'Create Motions',
            'description'   =>  'Create and edit own motions'
        ]);

        $editMotion =  Permission::updateOrCreate([
            'name'          =>  'administrate-motion',
            'display_name'  =>  'Administrate Motions',
            'description'   =>  'Administrate existing motions, enable them'
        ]);

        $showMotion =  Permission::updateOrCreate([
            'name'          =>  'show-motion',
            'display_name'  =>  'Show Motions',
            'description'   =>  'Show all non-active motions'
        ]);

        $deleteMotion =  Permission::updateOrCreate([
            'name'          =>  'delete-motion',
            'display_name'  =>  'Delete Motions',
            'description'   =>  'Delete motions'
        ]);

        $createComment =  Permission::updateOrCreate(['name' => 'create-comment'],[
            'name'          =>  'create-comment',
            'display_name'  =>  'Create Comments',
            'description'   =>  'Create and edit own comments'
        ]);

        $viewComment =  Permission::updateOrCreate([
            'name'          =>  'show-comment',
            'display_name'  =>  'Show Comments',
            'description'   =>  'View the comments and owners of comments that are not public'
        ]);

        $createCommentVote =  Permission::updateOrCreate([
            'name'          =>  'create-comment_vote',
            'display_name'  =>  'Create Comments',
            'description'   =>  'Can vote on comments'
        ]);

        $viewCommentVote =  Permission::updateOrCreate([
            'name'          =>  'view-comment_vote',
            'display_name'  =>  'Create Comments',
            'description'   =>  'Can view other users comment votes'
        ]);

        $deleteComment =  Permission::updateOrCreate([
            'name'          =>  'delete-comment',
            'display_name'  =>  'Delete Comments',
            'description'   =>  'Delete other peoples comments'
        ]);

        $createVote =  Permission::updateOrCreate([
            'name'          =>  'create-vote',
            'display_name'  =>  'Create Votes',
            'description'   =>  'Can vote and change own vote'
        ]);

        $showVote =  Permission::updateOrCreate([
            'name'          =>  'show-vote',
            'display_name'  =>  'Show Votes',
            'description'   =>  'Can see who placed a vote and a detailed record of all votes cast (like most recent vote)'
        ]);

        $createBackgroundImage =  Permission::updateOrCreate([
            'name'          =>  'create-background_image',
            'display_name'  =>  'Create Background Images',
            'description'   =>  'Can create and upload a background image'
        ]);

        $editBackgroundImage =  Permission::updateOrCreate([
            'name'          =>  'administrate-background_image',
            'display_name'  =>  'Edit Background Images',
            'description'   =>  'Can activate and edit other background images'
        ]);

        $createDepartment =  Permission::updateOrCreate(['name'=>'create-department'],[
            'name'          =>  'create-department',
            'display_name'  =>  'Create Departments',
            'description'   =>  'Can create a department'
        ]);

        $editDepartment =  Permission::updateOrCreate(['name'=>'administrate-department'],[
            'name'          =>  'administrate-department',
            'display_name'  =>  'Edit Departments',
            'description'   =>  'Can activate and departments'
        ]);


        $representative->perms()->sync(array($createComment->id,
                                             $createVote->id,
                                             $createMotion->id,
                                             $editMotion->id,
                                             $showMotion->id,
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
