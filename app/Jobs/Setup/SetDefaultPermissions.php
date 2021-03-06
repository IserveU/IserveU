<?php

namespace App\Jobs\Setup;

use App\Permission;
use App\Role;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SetDefaultPermissions implements ShouldQueue
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
        $admin = Role::updateOrCreate(['name' => 'administrator'],
          [
            'name'         => 'administrator',
            'display_name' => 'Full Administrator',
            'description'  => 'User is able to perform all database functions',
          ]
        );
        $citizen = Role::updateOrCreate(['name' => 'citizen'],
          [
            'name'         => 'citizen',
            'display_name' => 'Citizen',
            'description'  => 'A verified citizen. This is policed by the application as a person with a verified identiy and address',
          ]
        );

        $participant = Role::updateOrCreate(['name' => 'participant'],
          [
            'name'         => 'participant',
            'display_name' => 'Participant',
            'description'  => 'A user who can vote and comment by default',
        ]);

        $representative = Role::updateOrCreate(['name' => 'representative'],
        [
            'name'         => 'representative',
            'display_name' => 'Representative',
            'description'  => 'A representative who by default is deffered votes',
        ]);

        $editPermission = Permission::updateOrCreate(['name' => 'administrate-permission'],
        [
            'name'         => 'administrate-permission',
            'display_name' => 'Administrate Permissions',
            'description'  => 'Administrate the roles and permissions of other users',
        ]);

        $editUser = Permission::updateOrCreate(['name' => 'administrate-user'],
        [
            'name'         => 'administrate-user',
            'display_name' => 'Administrate Users',
            'description'  => 'Administrate existing other users, verify them',
        ]);

        $showUser = Permission::updateOrCreate(['name' => 'show-user'],
        [
            'name'         => 'show-user',
            'display_name' => 'Show Users',
            'description'  => 'See full existing (non-public) user profiles, their full details and recieve user summary emails',
        ]);

        $deleteUser = Permission::updateOrCreate(['name' => 'delete-user'],
        [
            'name'         => 'delete-user',
            'display_name' => 'Delete Users',
            'description'  => 'Able to delete users',
        ]);

        $createMotion = Permission::updateOrCreate(['name' => 'create-motion'],
        [
            'name'         => 'create-motion',
            'display_name' => 'Create Motions',
            'description'  => 'Create and edit own motions',
        ]);

        $editMotion = Permission::updateOrCreate(['name' => 'administrate-motion'],
        [
            'name'         => 'administrate-motion',
            'display_name' => 'Administrate Motions',
            'description'  => 'Administrate existing motions, enable them',
        ]);

        $showMotion = Permission::updateOrCreate(['name' => 'show-motion'],
        [
            'name'         => 'show-motion',
            'display_name' => 'Show Motions',
            'description'  => 'Show all non-active motions',
        ]);

        $deleteMotion = Permission::updateOrCreate(['name' => 'delete-motion'],
        [
            'name'         => 'delete-motion',
            'display_name' => 'Delete Motions',
            'description'  => 'Delete motions',
        ]);

        $createComment = Permission::updateOrCreate(['name' => 'create-comment'],
        [
            'name'         => 'create-comment',
            'display_name' => 'Create Comments',
            'description'  => 'Create and edit own comments',
        ]);

        $showComment = Permission::updateOrCreate(['name' => 'show-comment'],
        [
            'name'         => 'show-comment',
            'display_name' => 'Show Comments',
            'description'  => 'View the comments and owners of comments that are not public',
        ]);

        $createCommentVote = Permission::updateOrCreate(['name' => 'create-comment_vote'],
        [
            'name'         => 'create-comment_vote',
            'display_name' => 'Create Comments',
            'description'  => 'Can vote on comments',
        ]);

        $showCommentVote = Permission::updateOrCreate(['name' => 'show-comment_vote'],
        [
            'name'         => 'show-comment_vote',
            'display_name' => 'Create Comments',
            'description'  => 'Can show other users comment votes',
        ]);

        $deleteComment = Permission::updateOrCreate(['name' => 'delete-comment'],
        [
            'name'         => 'delete-comment',
            'display_name' => 'Delete Comments',
            'description'  => 'Delete other peoples comments',
        ]);

        $createVote = Permission::updateOrCreate(['name' => 'create-vote'],
        [
            'name'         => 'create-vote',
            'display_name' => 'Create Votes',
            'description'  => 'Can vote and change own vote',
        ]);

        $showVote = Permission::updateOrCreate(['name' => 'show-vote'],
        [
            'name'         => 'show-vote',
            'display_name' => 'Show Votes',
            'description'  => 'Can see who placed a vote and a detailed record of all votes cast (like most recent vote)',
        ]);

        $createDepartment = Permission::updateOrCreate(['name' => 'create-department'],
        [
            'name'         => 'create-department',
            'display_name' => 'Create Departments',
            'description'  => 'Can create a department',
        ]);

        $editDepartment = Permission::updateOrCreate(['name' => 'administrate-department'],
        [
            'name'         => 'administrate-department',
            'display_name' => 'Edit Departments',
            'description'  => 'Can activate and departments',
        ]);

        $representative->perms()->sync([$createComment->id,
                                             $createVote->id,
                                             $createMotion->id,
                                             $editMotion->id,
                                             $showMotion->id,
                                             $createCommentVote->id,
                                             $showCommentVote->id, ]);

        $citizen->perms()->sync([$createComment->id,
                                          $createCommentVote->id,
                                          $createMotion->id,
                                          $createVote->id, ]);

        $participant->perms()->sync([$createComment->id,
                                          $createCommentVote->id,
                                          $createMotion->id,
                                          $createVote->id, ]);

        $admin->perms()->sync([$editUser->id,
                                        $showUser->id,
                                        $deleteUser->id,
                                        $createComment->id,
                                        $createVote->id,
                                        $createMotion->id,
                                        $editMotion->id,
                                        $showMotion->id,
                                        $deleteMotion->id,
                                        $showComment->id,
                                        $showVote->id,
                                        $createCommentVote->id,
                                        $showCommentVote->id,
                                        $editPermission->id,
                                        $createDepartment->id,
                                        $editDepartment->id, ]);
    }
}
