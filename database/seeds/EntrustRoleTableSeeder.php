<?php

use Illuminate\Database\Seeder;

use App\Role;
use App\Permission;

class EntrustRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){

		$admin = new Role();
		$admin->name         			= 'administrator';
		$admin->display_name 			= 'Full Administrator';
		$admin->description  			= 'User is able to perform all database functions';
		$admin->save();
		
		$citizen = new Role();
		$citizen->name         			= 'citizen';
		$citizen->display_name 			= 'Citizen';
		$citizen->description  			= 'A verified citizen';
		$citizen->save();

		$councillor = new Role();
		$councillor->name         		= 'councillor';
		$councillor->display_name 		= 'City Councillor';
		$councillor->description  		= 'A City Councillor';
		$councillor->save();

		$editPermission					= 	new Permission();
		$editPermission->name			=	'administrate-permissions';
		$editPermission->display_name 	= 	'Administrate Permissions';
		$editPermission->description 	=	'Administrate the roles and permissions of other users';
		$editPermission->save();

		$editUser						= 	new Permission();
		$editUser->name					=	'administrate-users';
		$editUser->display_name 		= 	'Administrate Users';
		$editUser->description 			=	'Administrate existing other users, verify them';
		$editUser->save();

		$showUser						= 	new Permission();
		$showUser->name					=	'show-users';
		$showUser->display_name 		= 	'Show Users';
		$showUser->description 			=	'See full existing (non-public) user profiles and their full details';
		$showUser->save();

		$deleteUser						= 	new Permission();
		$deleteUser->name				=	'delete-users';
		$deleteUser->display_name 		= 	'Delete Users';
		$deleteUser->description 		=	'Able to delete users';
		$deleteUser->save();

		$createMotion					= 	new Permission();
		$createMotion->name				=	'create-motions';
		$createMotion->display_name 	= 	'Create Motion';
		$createMotion->description 		=	'Create and edit own motions';
		$createMotion->save();

		$editMotion						= 	new Permission();
		$editMotion->name				=	"administrate-motions";
		$editMotion->display_name 		= 	'Administrate Motion';
		$editMotion->description 		=	'Administrate existing motions, enable them';
		$editMotion->save();

		$showMotion						= 	new Permission();
		$showMotion->name				=	"show-motions";
		$showMotion->display_name 		= 	'Show Motion';
		$showMotion->description 		=	'Show all non-active motions';
		$showMotion->save();

		$deleteMotion					= 	new Permission();
		$deleteMotion->name				=	"delete-motions";
		$deleteMotion->display_name		= 	'Delete Motion';
		$deleteMotion->description 		=	'Delete motions';
		$deleteMotion->save();

		$createComment					= 	new Permission();
		$createComment->name			=	"create-comments";
		$createComment->display_name	= 	'Create a comment';
		$createComment->description 	=	'Create and edit own comment';
		$createComment->save();

		$viewComment					= 	new Permission();
		$viewComment->name				=	"view-comments";
		$viewComment->display_name		= 	'View Comments';
		$viewComment->description 		=	'View the comments and owners of comments that are not public';
		$viewComment->save();

		$createCommentVote					= new Permission;
		$createCommentVote->name			=	"create-comment_votes";
		$createCommentVote->display_name	= 	'Create Comments';
		$createCommentVote->description 	=	'Can vote on comments';
		$createCommentVote->save();
	
		$viewCommentVote					= new Permission;
		$viewCommentVote->name			=	"view-comment_votes";
		$viewCommentVote->display_name	= 	'Create Comments';
		$viewCommentVote->description 	=	'Can view other users comment votes';
		$viewCommentVote->save();


		$deleteComment					= 	new Permission();
		$deleteComment->name				=	"delete-comments";
		$deleteComment->display_name		= 	'Delete Comment';
		$deleteComment->description 		=	'Delete other peoples comments';
		$deleteComment->save();

		$createVote						= 	new Permission();
		$createVote->name				=	"create-votes";
		$createVote->display_name		= 	'Can vote (Create a vote)';
		$createVote->description 		=	'Can vote, vote on a comment, can edit';
		$createVote->save();

		$showVote						= 	new Permission();
		$showVote->name					=	"show-votes";
		$showVote->display_name			= 	'Can see other users votes';
		$showVote->description 			=	'Can see who placed a vote and a detailed record of all votes cast (like most recent vote)';
		$showVote->save();

		$createProperty					= 	new Permission();
		$createProperty->name			=	"create-properties";
		$createProperty->display_name	= 	'Create Property';
		$createProperty->description 	=	'Can create a property';
		$createProperty->save();

		$editProperty					= 	new Permission();
		$editProperty->name				=	"administrate-properties";
		$editProperty->display_name		= 	'Administrate Property';
		$editProperty->description 		=	'Can edit/delete a property';
		$editProperty->save();

		$createBackgroundImage						= 	new Permission();
		$createBackgroundImage->name				=	"create-background_images";
		$createBackgroundImage->display_name		= 	'Create Background Image';
		$createBackgroundImage->description 		=	'Can create and upload a background image';
		$createBackgroundImage->save();

		$editBackgroundImage						= 	new Permission();
		$editBackgroundImage->name					=	"administrate-background_images";
		$editBackgroundImage->display_name			= 	'Edit Background Image';
		$editBackgroundImage->description 			=	'Can activate and edit other background images';
		$editBackgroundImage->save();

		$createDepartment						= 	new Permission();
		$createDepartment->name					=	"create-department";
		$createDepartment->display_name			= 	'Create Department';
		$createDepartment->description 			=	'Can create a department';
		$createDepartment->save();

		$editDepartment							= 	new Permission();
		$editDepartment->name					=	"administrate-department";
		$editDepartment->display_name			= 	'Edit Department';
		$editDepartment->description 			=	'Can activate and departments';
		$editDepartment->save();

		$councillor->attachPermissions(array($createComment,
											 $createVote,
											 $createMotion,
											 $editMotion, 
											 $createCommentVote, 
											 $viewCommentVote));

		$citizen->attachPermissions(array($createBackgroundImage,
										  $createComment,
										  $createCommentVote,
										  $createMotion,
										  $createVote));

		$admin->attachPermissions(array($editUser,
										$showUser,
										$deleteUser,
										$createComment,
										$createVote,
										$createMotion,
										$editMotion,
										$showMotion,
										$deleteMotion,
										$createProperty,
										$editProperty,
										$viewComment,
										$showVote,
										$createCommentVote,
										$viewCommentVote,
										$editPermission,
										$createBackgroundImage,
										$editBackgroundImage,
										$createDepartment,
										$editDepartment));    
    }
}
