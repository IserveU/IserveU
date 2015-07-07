<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use League\Csv\Reader;

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

		$this->command->info("Reading in and seeding property data");
		$this->call('PropertySeeder');
		$this->command->info('seeding of property completed');

		$this->call('StaticSeeder'); //The fixed items in the table
		$this->command->info('Ethnic origins seeded'); 

		$this->call('DefaultUsers');
		$this->command->info('Default user/roles seeded'); 

		$this->call('SampleData');
		$this->command->info('SampleData'); 
		

	}

}


class PropertySeeder extends Seeder{

	public function run(){

		$property = new Property;
		$block = new PropertyBlock;
		$block->name = "77";
		$block->save();
		$poll = new PropertyPollDivision;
		$poll->name = "Weledeh";
		$poll->voting_station = "St. Patrick High School";
		$poll->save();
		$zone = new PropertyZoning;
		$zone->type = "";
		$zone->save();
		$propertyDescription = new PropertyDescription;
		$propertyDescription->description_code = "101";
		$propertyDescription->description = "Residential";
		$propertyDescription->save();
		$plan = new PropertyPlan;
		$plan->name = "C4576";
		$plan->save();
		$coordinate = new PropertyCoordinate;
		$coordinate->latitude = "62.45969223";
		$coordinate->longitude = "-114.3534692";
		$coordinate->block_id = $block->id;
		$coordinate->save();
		$property->roll_number = "77000903";
		$property->address = "5209";
		$property->street = "BROCK DR";
		$property->unit = "UNIT 3";
		$property->property_block_id 			= 	$block->id;
		$property->property_coordinate_id 		= 	$coordinate->id;
		$property->property_description_id		= 	$propertyDescription->id;
		$property->property_plan_id				= 	$plan->id;
		$property->property_poll_division_id 	= 	$poll->id;
		$property->property_zoning_id 			= 	$zone->id;
		$property->save();
				$propertyId = $property->id;
		$assessment = new PropertyAssesment;
		$assessment->land_value			= 28690;
		$assessment->improvement_value	= 72400;
		$assessment->other_value		= 0;
		$assessment->year				= "2015";
		$assessment->property_id		= $property->id;
		$assessment->save();

		$property1 = new Property;
		$block1 = new PropertyBlock;
		$block1->name = "169";
		$block1->save();
		$poll1 = $poll;				
		$zone1 = new PropertyZoning;
		$zone1->type = "";
		$zone1->save();
		$propertyDescription1 = $propertyDescription;
		$plan1 = new PropertyPlan;
		$plan1->name = "C2595";
		$plan1->save();
		$coordinate1 = new PropertyCoordinate;
		$coordinate1->latitude = "62.45933214";
		$coordinate1->longitude = "-114.3628542";
		$coordinate1->block_id = $block1->id;
		$coordinate1->save();
		$property1->roll_number = "0169000310";
		$property1->address = "19";
		$property1->street = "TRAILS END CRES";
		$property1->unit = "10";
		$property1->property_block_id 			= 	$block1->id;
		$property1->property_coordinate_id 		= 	$coordinate1->id;
		$property1->property_description_id		= 	$propertyDescription1->id;
		$property1->property_plan_id				= 	$plan1->id;
		$property1->property_poll_division_id 	= 	$poll1->id;
		$property1->property_zoning_id 			= 	$zone1->id;
		$property1->save();
		$propertyId1 = $property1->id;
		$assessment1 = new PropertyAssesment;
		$assessment1->land_value			= 96260;
		$assessment1->improvement_value	= 248300;
		$assessment1->other_value		= "0";
		$assessment1->year				= "2015";
		$assessment1->property_id		= $propertyId1;
		$assessment1->save();

		$property2 = new Property;
		$block2 = new PropertyBlock;
		$block2->name = "39";
		$block2->save();
		$poll2 = new PropertyPollDivision;
		$poll2->name = "YK Centre";
		$poll2->voting_station = "Northern United Place";
		$poll2->save();
		$zone2 = new PropertyZoning;
		$zone2->type = "DT";
		$zone2->save();
		$propertyDescription2 = $propertyDescription;
		$plan2 = new PropertyPlan;
		$plan2->name = "2389";
		$plan2->save();
		$coordinate2 = new PropertyCoordinate;
		$coordinate2->latitude = "62.45160061";
		$coordinate2->longitude = "-114.3705402";
		$coordinate2->block_id = $block2->id;
		$coordinate2->save();
		$property2->roll_number = "0039002300";
		$property2->address = "5105";
		$property2->street = "52 ST";
		$property2->unit = "65";
		$property2->property_block_id 			= 	$block2->id;
		$property2->property_coordinate_id 		= 	$coordinate2->id;
		$property2->property_description_id		= 	$propertyDescription2->id;
		$property2->property_plan_id				= 	$plan2->id;
		$property2->property_poll_division_id 	= 	$poll2->id;
		$property2->property_zoning_id 			= 	$zone2->id;
		$property2->save();
		$propertyId2 = $property2->id;
		$assessment2 = new PropertyAssesment;
		$assessment2->land_value			= 95740;
		$assessment2->improvement_value	= 126420;
		$assessment2->other_value		= "0";
		$assessment2->year				= "2015";
		$assessment2->property_id		= $propertyId2;
		$assessment2->save();

		$directory = getcwd();
		$directory .="/database/seeds/allykproperties.csv";

		$csv = Reader::createFromPath($directory);

		$allrows = $csv->setOffset(1)->fetchAll(); //because we don't want to insert the header
				
		/* csv format
		    [0] => Roll Number
		    [1] => Block
		    [2] => Plan
		    [3] => Unit
		    [4] => Civic Address
		    [5] => Street Name
		    [6] => Zoning
		    [7] => Description Code
		    [8] => Description
		    [9] => Assessment Improvement Value
		    [10] => Assessment Land Value
		    [11] => Assessment Other Value
		    [12] => Assessment Year
		    [13] => Assessment Total Value
		    [14] => Poll Division
		    [15] => Poll Division Name
		    [16] => Voting Station
		    [17] => Latitude
		    [18] => Longitude
		*/

/*
		foreach($allrows as $row){

			$property = Property::where('roll_number',trim($row[0]))->first();
			
			if($property==null){ //If the property hasn't been entered then we might not have all these, otherwise just check the assesment value hasn't changed

				$block = PropertyBlock::where('name',trim($row[1]))->first();
				if($block==null){
					$block = new PropertyBlock;
					$block->name = $row[1];
					$block->save();
				}

				$poll = PropertyPollDivision::where('name',trim($row[15]))->first();
				if($poll==null){
					$poll = new PropertyPollDivision;
					$poll->name = $row[15];
					$poll->voting_station = $row[16];
					$poll->save();
				}

				$zone = PropertyZoning::where('type',trim($row[6]))->first();
				if($zone==null){
					$zone = new PropertyZoning;
					$zone->type = $row[6];
					$zone->save();
				}

				$propertyDescription = PropertyDescription::where('description_code',trim($row[7]))->first();
				if($propertyDescription==null){
					$propertyDescription = new PropertyDescription;
					$propertyDescription->description_code = $row[7];
					$propertyDescription->description = $row[8];
					$propertyDescription->save();
				}

				$plan = PropertyPlan::where('name',trim($row[6]))->first();
				if($plan==null){
					$plan = new PropertyPlan;
					$plan->name = $row[2];
					$plan->save();
				}

				$coordinate = PropertyCoordinate::whereRaw("latitude = ".trim($row[17])." AND longitude = ".trim($row[18]))->first();
				if($coordinate==null){
					$coordinate = new PropertyCoordinate;
					$coordinate->latitude 	= 	$row[17];
					$coordinate->longitude 	= 	$row[18];
					$coordinate->block_id 		= 	$block->id;
					$coordinate->save();
				}

				$property = new Property;
				$property->roll_number 	= 	$row[0];
				$property->address 		= 	$row[4];
				$property->street 		= 	strtolower($row[5]);
				$property->unit 		= 	strtolower($row[3]);

				$property->property_block_id 			= 	$block->id;
				$property->property_coordinate_id 		= 	$coordinate->id;
				$property->property_poll_division_id 	= 	$poll->id;
				$property->property_zoning_id 			= 	$zone->id;
				$property->property_description_id		= 	$propertyDescription->id;
				$property->property_plan_id				= 	$plan->id;

				$property->save();
			}

			$row[9] = intval(str_replace(",","",$row[9]));
			$row[10] = intval(str_replace(",","",$row[10]));
			$row[11] = intval(str_replace(",","",$row[11]));
   			$propertyId = $property->id;

			$assessment = PropertyAssesment::whereRaw("improvement_value = $row[9] AND land_value = $row[10] AND other_value = $row[11] AND property_id = $propertyId")->first();
			if($assessment==null){
				$assessment = new PropertyAssesment;
  				$assessment->land_value			= $row[10];
  				$assessment->improvement_value	= $row[9];
  				$assessment->other_value		= $row[11];
  				$assessment->year				= $row[12];
  				$assessment->property_id			= $propertyId;
  				$assessment->save();
			}
		
		}*/
	} 
}

class StaticSeeder extends Seeder{

	public function run(){
		/* Commenting out because of issue on Jessicas Mac reading CSVS
		$directory = getcwd();
		$directory .="/database/seeds/ethnic_origins.csv";
		$csv = Reader::createFromPath($directory);
		$allrows = $csv->setOffset(1)->fetchAll(); //because we don't want to insert the header
		foreach($allrows as $row){
			$ethnicOrigin = new EthnicOrigin; //http://millenniumindicators.un.org/unsd/methods/m49/m49regin.htm
			$ethnicOrigin->description 	= $row[1];
			$ethnicOrigin->region 			= $row[0];
			$ethnicOrigin->save();
		} */

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
		$departments[2] = 'Local Agriculture';
		$departments[3] = 'Harvesting';
		$departments[4] = 'Tourism Economy';
		$departments[5] = 'Environmental Stewardship';
		$departments[6] = 'Solar Power';
		$departments[7] = 'Biomass Energy';
		$departments[8] = 'Skills Development';
		$departments[9] = 'Community Governance';
		$departments[10] = 'Community Quality of Life';

		foreach($departments as $key => $value){
			$department = new Department;
			$department->id 	= 	$key;
			$department->enabled = true;
			$department->name 	= 	$value;
			$department->save();
		} 	



	}
}

class DefaultUsers extends Seeder{

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
		$editPermission->name			=	'edit-permissions';
		$editPermission->display_name 	= 	'Edit Permissions';
		$editPermission->description 	=	'Edit the roles and permissions of users';
		$editPermission->save();

		$editUser						= 	new Permission();
		$editUser->name					=	'edit-users';
		$editUser->display_name 		= 	'Edit Users';
		$editUser->description 			=	'Edit existing users, verify them';
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
		$editMotion->name				=	"edit-motions";
		$editMotion->display_name 		= 	'Edit Motion';
		$editMotion->description 		=	'Edit existing motions, enable them';
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
		$createCommentVote->name			=	"create-commentvotes";
		$createCommentVote->display_name	= 	'Create Comments';
		$createCommentVote->description 	=	'Can vote on comments';
		$createCommentVote->save();
	
		$viewCommentVote					= new Permission;
		$viewCommentVote->name			=	"view-commentvotes";
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
		$editProperty->name				=	"edit-properties";
		$editProperty->display_name		= 	'Edit Property';
		$editProperty->description 		=	'Can edit/delete a property';
		$editProperty->save();

		$councilor->attachPermissions(array($createComment,$createVote,$createMotion,$editMotion));
		$citizen->attachPermissions(array($createComment,$createVote,$createCommentVote));
		$admin->attachPermissions(array($editUser,$showUser,$deleteUser,$createComment,$createVote,$createMotion,$editMotion,$showMotion,$deleteMotion,$createProperty,$editProperty,$viewComment,$showVote,$createCommentVote,$viewCommentVote,$editPermission));
		$userManager->attachPermissions(array($editUser,$showUser,$deleteUser));
		$unverified->attachPermissions(array($createComment,$createVote,$createCommentVote));

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
		$defaultUser->properties()->attach(1, ['verified_until'=>$date]);

		$defaultUser->attachRole($admin);

	}
}

class SampleData extends Seeder{

	private $password = "abcd1234";

	public function run(){
		$date = new DateTime;
		$date->add(new DateInterval('P3Y')); //Using to set verification

		//Issac Saunders 
		$ike = new User;	
		$ike->first_name = "Issac";
		$ike->middle_name = "Asher";
		$ike->last_name = "Saunders";
		$ike->email = "saunders.ike@gmail.com";
		$ike->public = 0;
		$ike->date_of_birth = "1995-11-09";
		$ethnicOrigin = EthnicOrigin::where('region','like','Northern Europe')->firstOrFail();
		$ike->ethnic_origin_id = $ethnicOrigin->id;
		$ike->password = $this->password;
		$ike->save();
		
		$property = Property::where('roll_number','0169000310')->firstOrFail(); //19 Trails End
		$ike->properties()->attach($property->id, ['verified_until'=>$date]);
		$ike->addUserRoleByName('citizen');

		//Jeremy Flatt (Foreign national who can't vote, no verified until)
		$jeremy = new User;
		$jeremy->first_name = "Jeremy";
		$jeremy->middle_name = "Edward";
		$jeremy->last_name = "Flatt";
		$jeremy->email = "jflatt@sosnewmedia.com";
		$jeremy->public = 0;
		$jeremy->date_of_birth = "1985-01-01";
		$ethnicOrigin = EthnicOrigin::where('region','like','Southern Europe')->firstOrFail();
		$jeremy->ethnic_origin_id = $ethnicOrigin->id;
		$jeremy->password = $this->password;

		$jeremy->save();

		$property = Property::where('roll_number','0169000310')->firstOrFail(); //19 Trails End
		$jeremy->properties()->attach($property->id);

		//Dane Mason
		$dane = new User;
		$dane->first_name = "Dane";
		$dane->middle_name = "";
		$dane->last_name = "Mason";
		$dane->email = "mason.dane@gmail.com";
		$dane->public = 0;
		$dane->date_of_birth = "1985-01-01";
		$date = new DateTime;
		$date->add(new DateInterval('P3Y'));
		$ethnicOrigin = EthnicOrigin::where('region','like','Northern Europe')->firstOrFail();
		$dane->ethnic_origin_id = $ethnicOrigin->id;
		$dane->password = $this->password;
	
		$dane->save();

		$property = Property::where('roll_number','0039002300')->firstOrFail(); //5105 52nd Street
		$dane->properties()->attach($property->id);
		$dane->addUserRoleByName('citizen');

		//Shinsaku Shiga (Another Foreign National)
		$shin = new User;
		$shin->first_name = "Shinsaku";
		$shin->middle_name = "";
		$shin->last_name = "Shiga";
		$shin->email = "s.shiga@gmail.com";
		$shin->public = 0;
		$shin->date_of_birth = "1984-01-01";
		$ethnicOrigin = EthnicOrigin::where('region','like','Eastern Asia')->firstOrFail();
		$shin->ethnic_origin_id = $ethnicOrigin->id;
		$shin->password = $this->password;
		$shin->save();

		$property = Property::where('roll_number','0169000310')->firstOrFail(); //Trails End
		$shin->properties()->attach($property->id);


		//Robin Young
		$robin = new User;
		$robin->first_name = "Joshua";
		$robin->middle_name = "Robin";
		$robin->last_name = "Young";
		$robin->email = "joshua.robin.young@gmail.com";
		$robin->public = 0;
		$robin->date_of_birth = "1984-01-01";
		$ethnicOrigin = EthnicOrigin::where('region','like','Northern Europe')->firstOrFail();
		$robin->ethnic_origin_id = $ethnicOrigin->id;
		$robin->password = $this->password;
		$robin->save();

		$property = Property::where('roll_number','0169000310')->firstOrFail(); //Trails End
		$robin->properties()->attach($property->id, ['verified_until'=>$date]);
		$robin->addUserRoleByName('citizen');


		//Popular and Unexpired Motion created by Jeremy Flatt
		$motionA = new Motion;
		$motionA->title = "Popular and Unexpired Motion";
		$motionA->text = "<p>This is a motion that is both <strong>popular</strong> and <strong>unexpired</strong> at the time of seeding the database</p><p>This motion was created by Jeremy Flatt, but he can not vote on it because he is a resident of Canada but not a citizen</p>";
		$motionA->summary = "This is a motion that is both popular and unexpired at the time of seeding the database";
		$date = new DateTime;
		$date->add(new DateInterval('P1M'));
		$motionA->closing = $date->format('Y-m-d');
		$motionA->user_id = $jeremy->id;
		$motionA->save();

			//Votes for it
				$voteA1 = new Vote;
				$voteA1->motion_id = $motionA->id;
				$voteA1->position = 1;
				$voteA1->user_id = $ike->id; //Ike
				$voteA1->save();

				$voteA2 = new Vote;
				$voteA2->motion_id = $motionA->id;
				$voteA2->position = 1;
				$voteA2->user_id = $dane->id; //Danes 
				$voteA2->save();

				$voteA3 = new Vote;
				$voteA3->motion_id = $motionA->id;
				$voteA3->position = 1;
				$voteA3->user_id = $robin->id; //Robin
				$voteA3->save();

			//Comments for it	
				$commentA1 = new Comment;
				$commentA1->text = "I, Ike Saunders, support this vote. 2 Other people have upvoted this and one has downvoted it";
				$commentA1->vote_id = $voteA1->id;
				$commentA1->save();
					$commentVotesA1 = new CommentVote;
					$commentVotesA1->vote_id = $voteA1->id; //Ikes vote
					$commentVotesA1->comment_id = $commentA1->id;
					$commentVotesA1->position = 1;
					$commentVotesA1->save();

					$commentVotesA1 = new CommentVote;
					$commentVotesA1->vote_id = $voteA2->id; //Danes vote
					$commentVotesA1->comment_id = $commentA1->id;
					$commentVotesA1->position = 1;
					$commentVotesA1->save();

					$commentVotesA1 = new CommentVote;
					$commentVotesA1->vote_id = $voteA3->id; //Robins vote
					$commentVotesA1->comment_id = $commentA1->id;
					$commentVotesA1->position = -1;
					$commentVotesA1->save();

				$commentA2 = new Comment;
				$commentA2->text = "I, Robin Young, support this vote";
				$commentA2->vote_id = $voteA3->id;
				$commentA2->save();
				

				$commentA3 = new Comment;
				$commentA3->text = "I, Dane, support this vote";
				$commentA3->vote_id = $voteA2->id;
				$commentA3->save();

					$commentVotesA3 = new CommentVote;
					$commentVotesA3->vote_id = $voteA2->id;
					$commentVotesA3->comment_id = $commentA3->id;
					$commentVotesA3->position = -1;
					$commentVotesA3->save();

		//Popular expired (passed) motion created by Ike
		$motionB = new Motion;
		$motionB->title = "Popular Expired Motion";
		$motionB->text = "<p>This is a motion that was <strong>popular</strong> and has <strong>expired</strong> at the time of seeding the database</p><p>This motion was created by Ike Saunders</p>";
		$motionB->summary = "This is a motion that was both popular and expired at the time of seeding the database";


		$date = new DateTime;
		$date->sub(new DateInterval('P1M'));
		$motionB->closing = $date->format('Y-m-d');
		$motionB->user_id = $ike->id;
		$motionB->save();


		//Votes for it
			$voteB1 = new Vote;
			$voteB1->motion_id = $motionB->id;
			$voteB1->position = 1;
			$voteB1->user_id = $ike->id; //Ike
			$voteB1->save();

			$voteB2 = new Vote;
			$voteB2->motion_id = $motionB->id;
			$voteB2->position = 1;
			$voteB2->user_id = $dane->id; //Dane
			$voteB2->save();

			$voteB3 = new Vote;
			$voteB3->motion_id = $motionB->id;
			$voteB3->position = 1;
			$voteB3->user_id = $robin->id; //Robin
			$voteB3->save();


		// Mixed active Motion
		$motionC = new Motion;
		$motionC->title = "Mixed Current Motion";
		$motionC->text = "<p>This is a motion that is <strong>mixed</strong> and <strong>current</strong> at the time of seeding the database</p><p>This motion was created by Shin</p>";
		$motionC->summary = "This is a motion that is mixed and current at the time of seeding the database";

		$date = new DateTime;
		$date->add(new DateInterval('P1M'));
		$motionC->closing = $date->format('Y-m-d');
		$motionC->user_id = $shin->id;
		$motionC->save();

		//Votes for it
			$voteC1 = new Vote;
			$voteC1->motion_id = $motionC->id;
			$voteC1->position = 1;
			$voteC1->user_id = $ike->id; //Ike
			$voteC1->save();



			$voteC2 = new Vote;
			$voteC2->motion_id = $motionC->id;
			$voteC2->position = 0;
			$voteC2->user_id = $dane->id; //Dane
			$voteC2->save();


			$voteC3 = new Vote;
			$voteC3->motion_id = $motionC->id;
			$voteC3->position = -1;
			$voteC3->user_id = $robin->id; //Robin
			$voteC3->save();

				$commentC1 = new Comment;
				$commentC1->text = "I, Ike, support this motion";
				$commentC1->vote_id = $voteC1->id;
				$commentC1->save();

				$commentC3 = new Comment;
				$commentC3->text = "I, Robin, do not support this motion";
				$commentC3->vote_id = $voteC3->id;
				$commentC3->save();


		}
	}


