<?php 
use \ApiTester;
use Faker\Factory as Faker;

class CommentTestCept
{
   
    protected $path = '/comment';
    
    public function _before(ApiTester $I)
    {
        $I->loginAsAdmin();
    }


    public function _after(){}



    //Create comments
    public function createComment(ApiTester $I, $role_id, $vote_id)
    {
        $faker = Faker::create();
        $I->haveHttpHeader('Content-Type', 'application/json');

        //test: role user can create comments
        if($I->seeInDataBase('permission_role', array('permission_id' => 9, 'role_id' => $role_id)))
        {
        	//User can create comment
        	for($i=0; $i<=10; $i++)
	        {
	            
	            $text = $faker->realText($maxNbChars = 200);

	            $I->sendPOST($this->path, array(
	                'text' => $text, 
	                'vote_id' => $vote_id,
	                'created_at' => date('Y-m-d H:i:s')
	                ));
	            $I->seeResponseCodeIs(200);
	            $I->seeResponseIsJson();
	            $I->seeResponseContainsJson(['text' => $text, 
	                                        'vote_id' => $vote_id]);
	        }
        }
        else
        {
        	return false;
        }
    }


    //Edit comments
    public function editComment(ApiTester $I, $id, $role_id, $vote_id)
    {
        $faker = Faker::create();
        $I->haveHttpHeader('Content-Type', 'application/json');

        //test: role user can edit comments
        if($I->seeInDataBase('permission_role', array('permission_id' => 9, 'role_id' => $role_id)))
        {
        	//User can create comment
        	for($i=0; $i<=10; $i++)
	        {
	            $text = $faker->realText($maxNbChars = 200);

	            $I->sendPATCH($this->path, array(
	            	'id' => $id,
	                'text' => $text, 
	                'vote_id' => $vote_id,
	                'updated_at' => date('Y-m-d H:i:s')
	                ));
	            $I->seeResponseCodeIs(200);
	            $I->seeResponseIsJson();
	            $I->seeResponseContainsJson(['id' => $id,
	            							'text' => $text, 
	                                        'vote_id' => $vote_id]);
	        }
        }
        else
        {
        	return false;
        }
    }


   	//Delete comment
    public function deleteComment(ApiTester $I, $id, $role_id)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        
         //test: role user can edit comments
        if($I->seeInDataBase('permission_role', array('permission_id' => 13, 'role_id' => $role_id)))
        {
	        $I->sendPATCH($this->path, array(
	        			'id' => $id,
		                'deleted_at' => date('Y-m-d H:i:s')
		                ));
	        $I->seeResponseCodeIs(200);
	        $I->seeResponseIsJson();

	        //field 'deleted_at' = NULL: comment has not been deleted
	        if($I->seeInDataBase('comments', array('id' => $id, 'deleted_at' => NULL)))
	        {
	        	return false;
	        }
	        //'deleted_at' <> NULL: comment deleted
	        else
	        {
	        	return true;
	        }
	    }
	    else
	    {
	    	return false;
	    }
    }

}
?>

