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


}
?>
