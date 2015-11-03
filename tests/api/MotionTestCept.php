<?php 
use \ApiTester;
use Faker\Factory as Faker;

class MotionTestCept
{
   
    protected $path = '/motion';
    
    public function _before(ApiTester $I)
    {
        $I->loginAsAdmin();
    }


    public function _after(){}

    //Create Motion
    public function createMotion(ApiTester $I, $user_id, $role_id)
    {
    	$active = rand(0,1);

    	//Ensure that a user can create a motion
    	//The user can not make a motion active
    	//A councilor can make the motion active
    	if(($I->seeInDataBase('permission_role', array('role_id' => $role_id, 'permission_id' => 5))
    			&& $active == 0) || ($role_id == 6))
    	{
	    	$faker = Faker::create();
	        $I->haveHttpHeader('Content-Type', 'application/json');

	        $title = $faker->realText($maxNbChars = 200);
	        $summary = $faker->realText($maxNbChars = 200);
	        $text = $faker->realText($maxNbChars = 200);
	        $I->sendPOST($this->path, array(
	            'title' => $title, 
	            'summary' => $summary,
	            'text' => $text,
	            'user_id' => $user_id,
	            'active' => $active
	            ));
	        $I->seeResponseCodeIs(200);
	        $I->seeResponseIsJson();
	        $id = $I->grabDataFromResponseByJsonPath('id');
	       	$I->seeResponseContainsJson(['title' => $title, 
						            'summary' => $summary,
						            'text' => $text,
						            'active' => $active]);
	    }
	    else
	    {
	    	return false;
	    }
    }


    //Edit Motion
    public function editMotion(ApiTester $I, $id, $role_id)
    {
    	$active = rand(0,1);

    	//A councilor can edit the motion active
    	if($role_id == 6)
    	{
	    	$faker = Faker::create();
	        $I->haveHttpHeader('Content-Type', 'application/json');

	        $title = $faker->realText($maxNbChars = 200);
	        $summary = $faker->realText($maxNbChars = 200);
	        $text = $faker->realText($maxNbChars = 200);
	        $I->sendPATCH($this->path, array(
	        	'id' => $id,
	            'title' => $title, 
	            'summary' => $summary,
	            'text' => $text
	            ));
	        $I->seeResponseCodeIs(200);
	        $I->seeResponseIsJson();
	        $id = $I->grabDataFromResponseByJsonPath('id');
	       	$I->seeResponseContainsJson(['title' => $title, 
						            'summary' => $summary,
						            'text' => $text]);
	    }
	    else
	    {
	    	return false;
	    }
    }


    //Close Motion
    public function closeMotion(ApiTester $I, $id)
    {
    	//Can't close motion if it's live
    	if($I->dontSeeInDataBase('motions', array('id' => $id, 'active' => 1)))
    	{
    		$I->sendPATCH($this->path, array(
	        	'closing' => date('Y-m-d H:i:s')
	            ));
	        $I->seeResponseCodeIs(200);
	        $I->seeResponseIsJson();
    	}
    	else
    	{
    		return false;
    	}
    }


    //Update active Motion
    public function activeMotion(ApiTester $I, $id, $active, $role)
    {
    	//A councilor can edit the motion
    	if($role_id == 6)
    	{
    		$I->sendPATCH($this->path, array(
	        	'active' => $active
	            ));
	        $I->seeResponseCodeIs(200);
	        $I->seeResponseIsJson();
    	}
    	else
    	{
    		return false;
    	}
    }


    //Delete Motion
    public function deleteMotion(ApiTester $I, $id, $user_id, $role_id)
    {
    	
    	//Only the user who created the motion and councilor can delete a motion
    	if(($role_id == 6) ||
    		$I->seeInDataBase('motions', array('id' => $id, 'user_id' => $user_id)))
    	{
	    	
	    	//If a motion has been voted, it can't be deleted
	    	//Only softdelete
    		if($I->seeInDataBase('votes', array('motion_id' => $id)))
    		{
    			$I->sendPATCH($this->path, array(
	        		'deleted_at' => date('Y-m-d H:i:s')
	            ));
		        $I->seeResponseCodeIs(200);
		        $I->seeResponseIsJson();
    		}
    		//Delete from database
    		else
    		{
		    	$I->haveHttpHeader('Content-Type', 'application/json');
		        $I->sendDELETE($this->path, array('id' => $id));
		        $I->seeResponseCodeIs(200);
		        $I->dontSeeRecord('motions', ['id' => $id]);
		    }
	    }
	    else
	    {
	    	return false;
	    }
    }
}
?>
