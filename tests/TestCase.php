<?php
use App\Role;
use App\Permission;

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

   
    public function setUp(){
        parent::setUp();

        foreach(\File::files(base_path("storage/logs")) as $filename){
            File::delete($filename);
        }


        \Config::set('mail.driver', 'log');
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
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    public function signIn($user = null)
    {     
        if(!$user){
            $user = factory(App\User::class,'verified')->create();
        }
        \Log::info('user ready');

       
        $this->user = $user;
        $this->actingAs($user);

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
}
