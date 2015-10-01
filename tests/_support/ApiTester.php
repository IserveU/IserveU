<?php


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
*/
class ApiTester extends \Codeception\Actor
{
    use _generated\ApiTesterActions;

   /**
    * Define custom actions here
    */


   	public function loginAsAdmin(){
		$user = [
			'email' => 'info@iserveu.ca',
			'password' => 'abcd1234'
			];

		$I = $this;
		$I->sendPOST('/authenticate', $user);
		$I->seeResponseIsJson();
		$token = json_encode($I->grabDataFromResponseByJsonPath('token'));
		$I->amBearerAuthenticated($token);

	}
}
