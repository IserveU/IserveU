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
use App\Motions;
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
		$this->command->info('Ethnic origin seeded'); 

		$this->call('DefaultUser');
		$this->command->info('Default users seeded'); 



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
				$property->unit 		= 	strtolower($row[4]);

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

		$defaultUser = new User;
		$random_pass = str_random(8);
		$this->command->info("\n\nADMIN LOGIN WITH: Password: ($random_pass) Email: info@iserveu.com \n\n");
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

