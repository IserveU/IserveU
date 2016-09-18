<?php
include_once('PolishedTest.php');



abstract class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    
    use PolishedTest;


    public $user;   // (public) Depreciated

    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';


    public static $aNormalMotion;


    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        ini_set('memory_limit','1028M');

        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }


    /**
     * To speed up tests so there is one motion that can be used to check
     * things on
     * @return App\Motion One fully stocked motion
     */
    public function getStaticMotion(){

        if(!static::$aNormalMotion){
            static::$aNormalMotion = aNormalMotion();
            \DB::commit(); //If triggered from a loction that uses database transactions
        }

        return static::$aNormalMotion;
    }





}
