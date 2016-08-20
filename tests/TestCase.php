<?php
use App\Role;
use App\Permission;

use PHPUnit_Framework_Assert as PHPUnit;

abstract class TestCase extends Illuminate\Foundation\Testing\TestCase
{


    public $user;

    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';
   
    protected $settings = [];
  
  
    protected $files = array(
        'test.png' =>
            [
                'name'          =>  'test.png',
                'type'      =>  'image/png'
            ],
        "test_a.jpg" =>
            [
                'name'          =>  'test_a.jpg',
                'type'      =>  'image/jpeg'
            ],
        "test_b.jpg" =>
            [
                'name'          =>  'test_b.jpg',
                'type'      =>  'image/jpeg'
            ],
        "test_b.pdf" =>
            [
                'name'          =>  'test_b.pdf',
                'type'      =>  'application/pdf'
            ]
    );


    public function setSettings($temporarySettings){
        foreach($temporarySettings as $key => $value){
            $this->settings[$key] = Setting::get($key); //Getting the before value
            Setting::set($key,$value);
        }
        Setting::save();
    }
  
    public function restoreSettings(){
        foreach($this->settings as $key => $value){
            $this->settings[$key] = Setting::get($key);
            Setting::set($key,$value);
        }
        Setting::save();
        $this->settings = [];
    }

    public function setEnv($temporaryEnv){
        foreach($temporaryEnv as $key => $value){
            \Config::set($key,$value);
        }
    }


    public function setUp(){
        parent::setUp();
        foreach(\File::files(base_path("storage/logs")) as $filename){
            File::delete($filename);
        }

        $this->setEnv(['mail.driver'=>'log']);
    }


    public function tearDown(){
        if(!empty($this->settings)){
            $this->restoreSettings();
        }
        
        parent::tearDown();
    }


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


    public function signIn($user = null){
        if(!$user){
            $user = factory(App\User::class)->create();
        }
        $this->user = $user;
        $this->actingAs($this->user);
        return $this;
    }


    public function signInAsAdmin($user = null){
        $this->signIn($user);
        $this->user->addUserRoleByName('administrator');
        return $this;
    }


    public function signInAsPermissionedUser($permissionName){
        $role = Role::create([
               'name'  => random_int(1000, 9999)."role_can_".$permissionName
        ]);
        $permission = Permission::where(['name'=>$permissionName])->first();
        
        $role->attachPermission($permission);
        $this->signIn();
        $this->user->attachRole($role);
        return $this;
    }
  

    public function signInAsRole($role){
        if(!$this->user){
            $this->signIn();
        }
        $this->user->addUserRoleByName($role);
        return $this;
    }


    public function getAnUploadedFile($filename = "test.png"){
        $faker = \Faker\Factory::create();
        $user = factory(App\User::class)->create();
        return [
            'file'  =>  new \Symfony\Component\HttpFoundation\File\UploadedFile(
                base_path("tests/File/$filename"), $filename, $this->files[$filename]['type'], 12000, null, TRUE
            ),
            'description'   => $faker->sentence,
            'user_id' =>        $user->id
        ];
       
    }



    public function assertHTTPExceptionStatus($expectedStatusCode, Closure $codeThatShouldThrow)
    {
        try 
        {
            $codeThatShouldThrow($this);
            $this->assertFalse(true, "An HttpException should have been thrown by the provided Closure.");
        } 
        catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) 
        {
            // assertResponseStatus() won't work because the response object is null
            $this->assertEquals(
                $expectedStatusCode,
                $e->getStatusCode(),
                sprintf("Expected an HTTP status of %d but got %d.", $expectedStatusCode, $e->getStatusCode())
            );
        }
    }



    public function getTokenForUser($user)
    {
        if (Auth::user()){
            Auth::logout();
        }

        Auth::loginUsingId($user->id);

        $this->post( '/authenticate',['email' => $user->email,'password' => 'abcd1234']);

        $content = json_decode($this->response->getContent());

        if(!$content){
            dd($this->response->getContent());
        }
        $this->assertObjectHasAttribute('token', $content, 'Token does not exists');

        return $this;
    }

    /**
     * Assert that the client response has a given code.
     *
     * @param  int  $code
     * @return $this
     */
    public function assertResponseStatus($code)
    {
        $actual = $this->response->getStatusCode();

        PHPUnit::assertEquals($code, $this->response->getStatusCode(), "Expected status code {$code}, got {$actual}. \n\n\n ResponseContent". $this->response->getContent());

        return $this;
    }

}
