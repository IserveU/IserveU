<?php

abstract class TestCase extends Illuminate\Foundation\Testing\TestCase {

	/**
	 * Creates the application.
	 *
	 * @return \Illuminate\Foundation\Application
	 */
	public function createApplication()
	{
		$app = require __DIR__.'/../bootstrap/app.php';

		$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

		return $app;
	}




    public static function htmlResponseToConsole($response,$functionTitle){


            $output ="\033[31m ####################################################################### ".$functionTitle." ###################################################################### \033[37m \n";
            $pos = strpos($response, '<span class="exception_message">');
            $output .=  strip_tags(substr($response, $pos, ( strpos($response, PHP_EOL, $pos) ) - $pos));
            
            $posOfStackTrace = strpos($response, '<ol class="traces list_exception">');
            $output .= "\n".strip_tags(substr($response, $posOfStackTrace, ( strpos($response, '</li>', $posOfStackTrace) ) - $posOfStackTrace))."\n\n";   

            return $output;

    }


    public static function makeUserAdmin($user){
    		Auth::login($user);
	    	$id = Auth::id();
		    var_dump($id);
		    die();
		    
		   
		    DB::table('role_user')->insert(array('user_id' => $id, 'role_id' => 4));

		    return $user;
    }
}
