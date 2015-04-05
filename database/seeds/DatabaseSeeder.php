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

		$this->call('DefaultUser');
		$this->command->info('Default users seeded'); 

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
					$coordinate->block 		= 	$block->id;
					$coordinate->save();
				}

				$property = new Property;
				$property->roll_number 	= 	$row[0];
				$property->address 		= 	$row[4];
				$property->street 		= 	strtolower($row[5]);
				$property->unit 		= 	strtolower($row[3]);

				$property->property_block 				= 	$block->id;
				$property->property_coordinate 			= 	$coordinate->id;
				$property->property_poll_division 				= 	$poll->id;
				$property->property_zoning 				= 	$zone->id;
				$property->property_description = 	$propertyDescription->id;
				$property->property_plan = 	$plan->id;

				$property->save();

			}


			$row[9] = intval(str_replace(",","",$row[9]));
			$row[10] = intval(str_replace(",","",$row[10]));
			$row[11] = intval(str_replace(",","",$row[11]));
   			$propertyId = $property->id;

			$assessment = PropertyAssesment::whereRaw("improvement_value = $row[9] AND land_value = $row[10] AND other_value = $row[11] AND property = $propertyId")->first();
			if($assessment==null){
				$assessment = new PropertyAssesment;
  				$assessment->land_value			= $row[10];
  				$assessment->improvement_value	= $row[9];
  				$assessment->other_value		= $row[11];
  				$assessment->year				= $row[12];
  				$assessment->property			= $propertyId;
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

class DefaultUser extends Seeder{

	public function run(){

		$random_pass = str_random(8);

		$defaultUser = new User;
		$this->command->info("\n\nADMIN LOGIN WITH: Password: (".$random_pass.") Email: info@iserveu.com \n\n");
		$defaultUser->first_name = "Change";
		$defaultUser->middle_name = "";
		$defaultUser->last_name = "Name";
		$defaultUser->email = "info@iserveu.ca";
		$defaultUser->public = 1;
		$defaultUser->administration = 1;
		$defaultUser->date_of_birth = "1987-04-01";
		$date = new DateTime;
		$date->add(new DateInterval('P3Y'));
		$defaultUser->verified_until = $date->format('Y-m-d');
		$defaultUser->ethnic_origin = 1;
		$defaultUser->password = Hash::make($random_pass);
		$defaultUser->property = 1;
		$defaultUser->save();

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
		$ike->administration = 0;
		$ike->date_of_birth = "1995-11-09";
		$date = new DateTime;
		$date->add(new DateInterval('P3Y'));
		$ike->verified_until = $date->format('Y-m-d');
		$ethnicOrigin = EthnicOrigin::where('region','like','Northern Europe')->firstOrFail();
		$ike->ethnic_origin = $ethnicOrigin->id;
		$ike->password = Hash::make($this->password);
		$property = Property::where('roll_number','0169000310')->firstOrFail(); //19 Trails End
		$ike->property = $property->id;
		$ike->save();

		//Jeremy Flatt (Foreign national who can't vote, no verified until)
		$jeremy = new User;
		$jeremy->first_name = "Jeremy";
		$jeremy->middle_name = "Edward";
		$jeremy->last_name = "Flatt";
		$jeremy->email = "jflatt@sosnewmedia.com";
		$jeremy->public = 0;
		$jeremy->administration = 0;
		$jeremy->date_of_birth = "1985-01-01";
		$ethnicOrigin = EthnicOrigin::where('region','like','Southern Europe')->firstOrFail();
		$jeremy->ethnic_origin = $ethnicOrigin->id;
		$jeremy->password = Hash::make($this->password);
		$property = Property::where('roll_number','0169000310')->firstOrFail(); //19 Trails End
		$jeremy->property = $property->id;
		$jeremy->save();

		//Dane Mason
		$dane = new User;
		$dane->first_name = "Dane";
		$dane->middle_name = "";
		$dane->last_name = "Mason";
		$dane->email = "mason.dane@gmail.com";
		$dane->public = 0;
		$dane->administration = 0;
		$dane->date_of_birth = "1985-01-01";
		$date = new DateTime;
		$date->add(new DateInterval('P3Y'));
		$dane->verified_until = $date->format('Y-m-d');
		$ethnicOrigin = EthnicOrigin::where('region','like','Northern Europe')->firstOrFail();
		$dane->ethnic_origin = $ethnicOrigin->id;
		$dane->password = Hash::make($this->password);
		$property = Property::where('roll_number','0039002300')->firstOrFail(); //5105 52nd Street
		$dane->property = $property->id;
		$dane->save();

		//Shinsaku Shiga (Another Foreign National)
		$shin = new User;
		$shin->first_name = "Shinsaku";
		$shin->middle_name = "";
		$shin->last_name = "Shiga";
		$shin->email = "s.shiga@gmail.com";
		$shin->public = 0;
		$shin->administration = 0;
		$shin->date_of_birth = "1984-01-01";
		$ethnicOrigin = EthnicOrigin::where('region','like','Eastern Asia')->firstOrFail();
		$shin->ethnic_origin = $ethnicOrigin->id;
		$shin->password = Hash::make($this->password);
		$property = Property::where('roll_number','0169000310')->firstOrFail(); //Trails End
		$shin->property = $property->id;
		$shin->save();

		//Robin Young
		$robin = new User;
		$robin->first_name = "Joshua";
		$robin->middle_name = "Robin";
		$robin->last_name = "Young";
		$robin->email = "joshua.robin.young@gmail.com";
		$robin->public = 0;
		$robin->administration = 0;
		$robin->date_of_birth = "1984-01-01";
		$ethnicOrigin = EthnicOrigin::where('region','like','Northern Europe')->firstOrFail();
		$robin->ethnic_origin = $ethnicOrigin->id;
		$robin->password = Hash::make($this->password);
		$property = Property::where('roll_number','0169000310')->firstOrFail(); //Trails End
		$robin->property = $property->id;
		$robin->save();



		//Popular and Unexpired Motion created by Jeremy Flatt
		$motionA = new Motion;
		$motionA->title = "Popular and Unexpired Motion";
		$motionA->text = "<p>This is a motion that is both <strong>popular</strong> and <strong>unexpired</strong> at the time of seeding the database</p><p>This motion was created by Jeremy Flatt, but he can not vote on it because he is a resident of Canada but not a citizen</p>";
		$date = new DateTime;
		$date->add(new DateInterval('P1M'));
		$motionA->closing_date = $date->format('Y-m-d');
		$motionA->user = $jeremy->id;
		$motionA->save();

			//Votes for it
				$voteA1 = new Vote;
				$voteA1->motion = $motionA->id;
				$voteA1->position = 1;
				$voteA1->user = $ike->id; //Ike
				$voteA1->save();

				$voteA2 = new Vote;
				$voteA2->motion = $motionA->id;
				$voteA2->position = 1;
				$voteA2->user = $dane->id; //Danes 
				$voteA2->save();

				$voteA3 = new Vote;
				$voteA3->motion = $motionA->id;
				$voteA3->position = 1;
				$voteA3->user = $robin->id; //Robin
				$voteA3->save();

			//Comments for it	
				$commentA1 = new Comment;
				$commentA1->motion = $motionA->id;
				$commentA1->text = "I, Ike Saunders, support this vote. 2 Other people have upvoted this and one has downvoted it";
				$commentA1->user = $ike->id;
				$commentA1->save();
					$commentVotesA1 = new CommentVote;
					$commentVotesA1->vote = $voteA1->id; //Ikes vote
					$commentVotesA1->comment = $commentA1->id;
					$commentVotesA1->position = 1;
					$commentVotesA1->save();

					$commentVotesA1 = new CommentVote;
					$commentVotesA1->vote = $voteA2->id; //Danes vote
					$commentVotesA1->comment = $commentA1->id;
					$commentVotesA1->position = 1;
					$commentVotesA1->save();

					$commentVotesA1 = new CommentVote;
					$commentVotesA1->vote = $voteA3->id; //Robins vote
					$commentVotesA1->comment = $commentA1->id;
					$commentVotesA1->position = -1;
					$commentVotesA1->save();

				$commentA2 = new Comment;
				$commentA2->motion = $motionA->id;
				$commentA2->text = "I, Robin Young, support this vote";
				$commentA2->user = $robin->id;
				$commentA2->save();
				

				$commentA3 = new Comment;
				$commentA3->motion = $motionA->id;
				$commentA3->text = "I, Dane, support this vote";
				$commentA3->user = $dane->id;
				$commentA3->save();

					$commentVotesA3 = new CommentVote;
					$commentVotesA3->vote = $voteA2->id;
					$commentVotesA3->comment = $commentA3->id;
					$commentVotesA3->position = -1;
					$commentVotesA3->save();




		//Popular expired (passed) motion created by Ike
		$motionB = new Motion;
		$motionB->title = "Popular Expired Motion";
		$motionB->text = "<p>This is a motion that was <strong>popular</strong> and has <strong>expired</strong> at the time of seeding the database</p><p>This motion was created by Ike Saunders</p>";
		$date = new DateTime;
		$date->sub(new DateInterval('P1M'));
		$motionB->closing_date = $date->format('Y-m-d');
		$motionB->user = $ike->id;
		$motionB->save();


		//Votes for it
			$voteB1 = new Vote;
			$voteB1->motion = $motionB->id;
			$voteB1->position = 1;
			$voteB1->user = $ike->id; //Ike
			$voteB1->save();

			$voteB2 = new Vote;
			$voteB2->motion = $motionB->id;
			$voteB2->position = 1;
			$voteB2->user = $dane->id; //Dane
			$voteB2->save();

			$voteB3 = new Vote;
			$voteB3->motion = $motionB->id;
			$voteB3->position = 1;
			$voteB3->user = $robin->id; //Robin
			$voteB3->save();


		// Mixed active Motion
		$motionC = new Motion;
		$motionC->title = "Mixed Current Motion";
		$motionC->text = "<p>This is a motion that is <strong>mixed</strong> and <strong>current</strong> at the time of seeding the database</p><p>This motion was created by Shin</p>";
		$date = new DateTime;
		$date->add(new DateInterval('P1M'));
		$motionC->closing_date = $date->format('Y-m-d');
		$motionC->user = $shin->id;
		$motionC->save();

		//Votes for it
			$voteC1 = new Vote;
			$voteC1->motion = $motionC->id;
			$voteC1->position = 1;
			$voteC1->user = $ike->id; //Ike
			$voteC1->save();

				$commentC3 = new Comment;
				$commentC3->motion = $motionC->id;
				$commentC3->text = "I, Robin, do not support this motion";
				$commentC3->user = $robin->id;
				$commentA3->save();

			$voteC2 = new Vote;
			$voteC2->motion = $motionC->id;
			$voteC2->position = 0;
			$voteC2->user = $dane->id; //Dane
			$voteC2->save();

				$commentC2 = new Comment;
				$commentC2->motion = $motionC->id;
				$commentC2->text = "I, Dane, do not support this motion";
				$commentC2->user = $dane->id;
				$commentC2->save();

			$voteC3 = new Vote;
			$voteC3->motion = $motionC->id;
			$voteC3->position = -1;
			$voteC3->user = $robin->id; //Robin
			$voteC3->save();


				$commentC3 = new Comment;
				$commentC3->motion = $motionC->id;
				$commentC3->text = "I, Robin, do not support this motion";
				$commentC3->user = $robin->id;
				$commentA3->save();
		


	}

}

