<?php

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

class VoteTest extends TestCase {

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testPlaceVote(){

		$randomString = "I".str_random(4)."D";
		$user = new User;
		$user->first_name = "First Name";
		$user->middle_name = $randomString;
		$user->last_name = "Last Name";
		$user->email = $randomString."@iserveu.ca";
		$user->public = 0;
		$user->date_of_birth = "1984-01-01";
		$ethnicOrigin = EthnicOrigin::where('region','like','Eastern Asia')->firstOrFail();
		$user->ethnic_origin_id = $ethnicOrigin->id;
		$user->password = Hash::make($randomString);
		$user->save();

		$property = Property::where('roll_number','0169000310')->firstOrFail(); //Trails End
		$user->properties()->attach($property->id);

		$citizen = Role::where('name','citizen');

		$user->attachRole($citizen);

		$this->be($user);
		$response = $this->call('GET', '/votes/create',['motion_id'=>1,'position'=>1]);

		echo $response->getContent();
		$this->assertEquals(200, $response->getStatusCode()); 


	}

}
