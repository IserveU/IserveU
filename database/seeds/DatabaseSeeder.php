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

class DatabaseSeeder extends Seeder {


	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

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
		
		}
	}
}

class StaticSeeder extends Seeder{

	public function run(){
		$directory = getcwd();
		$directory .="/database/seeds/ethnic_origins.csv";
		$csv = Reader::createFromPath($directory);
		$allrows = $csv->setOffset(1)->fetchAll(); //because we don't want to insert the header
		foreach($allrows as $row){
			$ethnicOrigin = new EthnicOrigin; //http://millenniumindicators.un.org/unsd/methods/m49/m49regin.htm
			$ethnicOrigin->description 	= $row[1];
			$ethnicOrigin->region 			= $row[0];
			$ethnicOrigin->save();
		} 

	}
}

class DefaultUsers extends Seeder{

	public function run(){

				/*
		Role::create(['id'	=> 	1,	'name'		=> 	'User Editor'	, 		'description'	=> 'Able to edit and verify other users addresses and identities']);
		Role::create(['id'	=>	2,	'name'		=> 	'Motion Creator', 		'description'	=> 'Able to create and edit own motions']);
		Role::create(['id'	=>	3,	'name'		=> 	'Voter'	, 				'description'	=> 'Able to cast votes']);
		Role::create(['id'	=>	4,	'name'		=> 	'Property Editor',		'description'	=> 'Able to adjust the property related section']);
		Role::create(['id'	=>	5,	'name'		=> 	'Intrepid'	,			'description'	=> 'Able to cast votes from the uncast pool, unable to be hidden']);
		*/

		$admin = new Role();
		$admin->name         = 'administrator';
		$admin->display_name = 'Full Administrator';
		$admin->description  = 'User is able to perform all database functions';
		$admin->save();

		$editUser				= 	new Permission();
		$editUser->name				=	'edit-user';
		$editUser->display_name = 	'Edit Users';
		$editUser->description 	=	'Edit existing users';
		$editUser->save();
		$admin->attachPermission($editUser);

		$showUser				= 	new Permission();
		$showUser->name			=	'show-user';
		$showUser->display_name = 	'Show Users';
		$showUser->description 	=	'See full existing (non-public) user profiles';
		$showUser->save();
		$admin->attachPermission($showUser);

		$deleteUser					= 	new Permission();
		$deleteUser->name			=	'delete-user';
		$deleteUser->display_name 	= 	'Delete Users';
		$deleteUser->description 	=	'Able to delete users';
		$deleteUser->save();
		$admin->attachPermission($deleteUser);


		$random_pass = str_random(8);

		$defaultUser = new User;
		$this->command->info("\n\nADMIN LOGIN WITH: Password: (".$random_pass.") Email: info@iserveu.ca \n\n");
		$defaultUser->first_name = "Change";
		$defaultUser->middle_name = "";
		$defaultUser->last_name = "Name";
		$defaultUser->email = "info@iserveu.ca";
		$defaultUser->public = 1;
		$defaultUser->date_of_birth = "1987-04-01";
		$date = new DateTime;
		$date->add(new DateInterval('P3Y'));
		$defaultUser->ethnic_origin_id = 1;
		$defaultUser->password = Hash::make($random_pass);
		$defaultUser->property_id = 1;
		$defaultUser->save();


	

		$defaultUser->attachRole($admin);


	}
}

class SampleData extends Seeder{

	private $password = "abcd1234";

	public function run(){

		//Issac Saunders 
		$ike = new User;	
		$ike->first_name = "Issac";
		$ike->middle_name = "Asher";
		$ike->last_name = "Saunders";
		$ike->email = "saunders.ike@gmail.com";
		$ike->public = 0;
		$ike->date_of_birth = "1995-11-09";
		$date = new DateTime;
		$date->add(new DateInterval('P3Y'));
		$ethnicOrigin = EthnicOrigin::where('region','like','Northern Europe')->firstOrFail();
		$ike->ethnic_origin_id = $ethnicOrigin->id;
		$ike->password = Hash::make($this->password);
		$property = Property::where('roll_number','0169000310')->firstOrFail(); //19 Trails End
		$ike->property_id = $property->id;
		$ike->save();

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
		$jeremy->password = Hash::make($this->password);
		$property = Property::where('roll_number','0169000310')->firstOrFail(); //19 Trails End
		$jeremy->property_id = $property->id;
		$jeremy->save();

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
		$dane->password = Hash::make($this->password);
		$property = Property::where('roll_number','0039002300')->firstOrFail(); //5105 52nd Street
		$dane->property_id = $property->id;
		$dane->save();

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
		$shin->password = Hash::make($this->password);
		$property = Property::where('roll_number','0169000310')->firstOrFail(); //Trails End
		$shin->property_id = $property->id;
		$shin->save();

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
		$robin->password = Hash::make($this->password);
		$property = Property::where('roll_number','0169000310')->firstOrFail(); //Trails End
		$robin->property_id = $property->id;
		$robin->save();



		//Popular and Unexpired Motion created by Jeremy Flatt
		$motionA = new Motion;
		$motionA->title = "Popular and Unexpired Motion";
		$motionA->text = "<p>This is a motion that is both <strong>popular</strong> and <strong>unexpired</strong> at the time of seeding the database</p><p>This motion was created by Jeremy Flatt, but he can not vote on it because he is a resident of Canada but not a citizen</p>";
		$date = new DateTime;
		$date->add(new DateInterval('P1M'));
		$motionA->closing_date = $date->format('Y-m-d');
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
				$commentA1->motion_id = $motionA->id;
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
				$commentA2->motion_id = $motionA->id;
				$commentA2->text = "I, Robin Young, support this vote";
				$commentA2->vote_id = $voteA3->id;
				$commentA2->save();
				

				$commentA3 = new Comment;
				$commentA3->motion_id = $motionA->id;
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
		$date = new DateTime;
		$date->sub(new DateInterval('P1M'));
		$motionB->closing_date = $date->format('Y-m-d');
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
		$date = new DateTime;
		$date->add(new DateInterval('P1M'));
		$motionC->closing_date = $date->format('Y-m-d');
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
				$commentC1->motion_id = $motionC->id;
				$commentC1->text = "I, Ike, support this motion";
				$commentC1->vote_id = $voteC1->id;
				$commentC1->save();

				$commentC3 = new Comment;
				$commentC3->motion_id = $motionC->id;
				$commentC3->text = "I, Robin, do not support this motion";
				$commentC3->vote_id = $voteC3->id;
				$commentC3->save();

	

		
		


	}

}

