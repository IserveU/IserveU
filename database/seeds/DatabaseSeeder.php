<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query;



use App\PropertyBlock;
use App\PropertyPollDivision;
use App\PropertyAssesment;
use App\PropertyCoordinate;
use App\PropertyPlan;
use App\Property;
use App\PropertyZoning;
use App\PropertyDescription;
use App\EthnicOrigin;
use App\User;
use App\Motion;
use App\Comment;
use App\Vote;
use App\CommentVote;
use App\Role;
use App\Permission;
use App\Verification;
use App\Department;

//use Illuminate\Support\Facades\Hash;


class DatabaseSeeder extends Seeder {


	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();


		$this->call('StaticSeeder'); //The fixed items in the table
		$this->command->info('Ethnic origins seeded'); 

		$this->call('DefaultUsers');
		$this->command->info('Default user/roles seeded'); 

		// factory('App\User', 100)->create();
		// $this->call(MotionTableSeeder::class);
		// $this->call(CommentTableSeeder::class);

	}

}


class StaticSeeder extends Seeder {


	public function run(){

		$unknown = new EthnicOrigin;
		$unknown->description = "No predominant or known ancestry";
		$unknown->region = "Unknown";
		$unknown->save();

		$unknown = new EthnicOrigin;
		$unknown->description = "Kenya, Rwanda, Zimbabwe, South Sudan";
		$unknown->region = "Eastern Africa";
		$unknown->save();

		$unkown = new EthnicOrigin;
		$unknown->description = "Angola, Cameroon, Chad, Congo";
		$unknown->region = "Middle Africa";
		$unknown->save();

		$unknown = new EthnicOrigin;
		$unknown->description = "Algeria, Tunisian, Libya, Western Sahara";
		$unknown->region = "Northern Africa";
		$unknown->save();

		$unknown = new EthnicOrigin;
		$unknown->description = "Botswana, Namibia, Swaziland";
		$unknown->region = "Southern Africa";
		$unknown->save();

		$unknown = new EthnicOrigin;
		$unknown->description = "Burkina Faso, Niger, Senegal";
		$unknown->region = "Western Africa"; 
		$unknown->save();

		$unknown = new EthnicOrigin;
		$unknown->description = "Cuba, Haiti, Trinidad";
		$unknown->region = "Caribbean";
		$unknown->save();

		$unknown = new EthnicOrigin;
		$unknown->description = "Mexico, Panama, Costa Rica";
		$unknown->region = "Central America";
		$unknown->save();

		$unknown = new EthnicOrigin;
		$unknown->description = "Argentina, Brazuk, Venezuela";
		$unknown->region = "South America";
		$unknown->save();

		$unknown = new EthnicOrigin;
		$unknown->description = "Canada, Greenland, United States of America"; 
		$unknown->region = "Northern America";
		$unknown->save();

		$unknown = new EthnicOrigin;
		$unknown->description = "Kazakhstan, Tajikistan";
		$unknown->region = "Central Asia";
		$unknown->save();

		$unknown = new EthnicOrigin;
		$unknown->description = "China, Japan, Korea";
		$unknown->region = "Eastern Asia";
		$unknown->save();

		$unknown = new EthnicOrigin;
		$unknown->description = "Afghanistan, India, Sri Lanka, Nepal";
		$unknown->region = "Southern Asia";
		$unknown->save();

		$unknown = new EthnicOrigin;
		$unknown->description = "Philippines, Thailand, Indonesia, Vietnam";
		$unknown->region = "South-Eastern Asia";
		$unknown->save();

		$unknown = new EthnicOrigin;
		$unknown->description = "Turkey, Iraq, Yemen";
		$unknown->region = "Western Asia";
		$unknown->save();

		$unknown = new EthnicOrigin;
		$unknown->description = "Belarus, Poland, Russia";
		$unknown->region = "Eastern Europe";
		$unknown->save();

		$unknown = new EthnicOrigin;
		$unknown->description = "UK, Iceland, Finland, Norway";
		$unknown->region = "Northern Europe";
		$unknown->save();

		$unknown = new EthnicOrigin;
		$unknown->description = "Italy, Greece, Spain";
		$unknown->region = "Southern Europe";
		$unknown->save();

		$unknown = new EthnicOrigin;
		$unknown->description = "Germany, Netherlands, Belgium, France";
		$unknown->region = "Western Europe";
		$unknown->save();

		$unknown = new EthnicOrigin;
		$unknown->description = "Australia, New Zealand";
		$unknown->region = "Australasia";
		$unknown->save();

		$unknown = new EthnicOrigin;
		$unknown->description = "Fiji, Vanuatu, Solomon Islands";
		$unknown->region = "Melanesia";
		$unknown->save();

		$unknown = new EthnicOrigin;
		$unknown->description = "Guam, Kiribati, Nauru";
		$unknown->region = "Micronesia";
		$unknown->save();

		$unknown = new EthnicOrigin;
		$unknown->description = "Cook Islands, Samoa, Tonga";
		$unknown->region = "Polynesia";
		$unknown->save(); 


		$departments[1] = 'Unknown';
		$departments[2] = 'City Administrator';
		$departments[3] = 'Community Services';
		$departments[4] = 'Corporate Services';
		$departments[5] = 'Communications and Economic Development';
		$departments[6] = 'Planning and Development';
		$departments[7] = 'Public Safety';
		$departments[8] = 'Public Works and Engineering';

		foreach($departments as $key => $value){
			$department = new Department;
			$department->id 	= 	$key;
			$department->active = true;
			$department->name 	=   $value;
			$department->save();
		}
	}
}

class DefaultUsers extends Seeder {

	public function run(){

		$admin = new Role();
		$admin->name         			= 'administrator';
		$admin->display_name 			= 'Full Administrator';
		$admin->description  			= 'User is able to perform all database functions';
		$admin->save();

		$userManager = new Role();
		$userManager->name         		= 'user-admin';
		$userManager->display_name 		= 'User Administrator';
		$userManager->description  		= 'User is able to update, verify and delete users';
		$userManager->save();

		$motionManager = new Role();
		$motionManager->name         	= 'motion-admin';
		$motionManager->display_name 	= 'Motion Administrator';
		$motionManager->description  	= 'User is able to edit, translate and delete motions';
		$motionManager->save();

		$citizen = new Role();
		$citizen->name         			= 'citizen';
		$citizen->display_name 			= 'Citizen';
		$citizen->description  			= 'A verified citizen';
		$citizen->save();

		$unverified = new Role();
		$unverified->name         		= 'unverified';
		$unverified->display_name 		= 'Unverified Citizen';
		$unverified->description  		= 'A person who is not verified';
		$unverified->save();

		$councilor = new Role();
		$councilor->name         		= 'councilor';
		$councilor->display_name 		= 'City Councilor';
		$councilor->description  		= 'A City Councilor';
		$councilor->save();

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

		$councilor->attachPermissions(array($createComment,$createVote,$createMotion,$editMotion));
		$citizen->attachPermissions(array($createComment,$createVote,$createCommentVote,$createBackgroundImage));
		$admin->attachPermissions(array($editUser,$showUser,$deleteUser,$createComment,$createVote,$createMotion,$editMotion,$showMotion,$deleteMotion,$createProperty,$editProperty,$viewComment,$showVote,$createCommentVote,$viewCommentVote,$editPermission,$createBackgroundImage,$editBackgroundImage,$createDepartment,$editDepartment));
		$userManager->attachPermissions(array($editUser,$showUser,$deleteUser));
		$unverified->attachPermissions(array($createComment,$createVote,$createCommentVote,$createBackgroundImage));

		$random_pass = 'abcd1234'; //str_random(8);

		$defaultUser = new User;
		$this->command->info("\n\nADMIN LOGIN WITH: Password: (".$random_pass.") Email: info@iserveu.ca \n\n");
		$defaultUser->first_name = "Change";
		$defaultUser->middle_name = "";
		$defaultUser->last_name = "Name";
		$defaultUser->email = "info@iserveu.ca";
		$defaultUser->public = 1;
		$defaultUser->date_of_birth = "1987-04-01";

		$defaultUser->ethnic_origin_id = 1;
		$defaultUser->password = $random_pass;
	
		$defaultUser->save();

		$date = new DateTime;
		$date->add(new DateInterval('P3Y'));

		$defaultUser->attachRole($admin);

	}
}