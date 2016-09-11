<?php
use App\Role;
use App\Permission;

use PHPUnit_Framework_Assert as PHPUnit;

abstract class TestCase extends Illuminate\Foundation\Testing\TestCase
{


    protected $user;

    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';


  //  protected $paywallOn; Depreciated

    protected $settings = [];
    protected $env = [];

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
            $this->settings[$key] = \App\Setting::get($key); //Getting the before value
            \App\Setting::set($key,$value);
        }
        \App\Setting::save();
    }




    public function restoreSettings(){

        foreach($this->settings as $key => $value){
            if($value == null){
                \App\Setting::forget($key);
            } else {

                $this->settings[$key] = \App\Setting::get($key);
                \App\Setting::set($key,$value);    
            }            
        }

        \App\Setting::save();
        $this->settings = [];
    }

    public function setEnv($temporaryEnv){
        foreach($temporaryEnv as $key => $value){
            $this->env[$key] = getenv($key); //Getting the before value
            putenv("$key=$value");
        }
    }

    public function restoreEnv(){
        foreach($this->env as $key => $value){
            putenv("$key=$value");
        }
        $this->env = [];
    }

    public function setUp(){
        parent::setUp();

        foreach(\File::files(base_path("storage/logs")) as $filename){
            File::delete($filename);
        }

        //$this->clearLocalDevices();
        \Config::set('mail.driver', 'log');
    }

    public function tearDown(){
        if(!empty($this->settings)){
            $this->restoreSettings();
        }

        if(!empty($this->env)){
            $this->restoreEnv();
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
        if(!is_array($permissionName)){
            $permissionName = [$permissionName];
        }

        $role = Role::create([
               'name'  => random_int(1000, 9999)."role_can_".$permissionName[0]
        ]);
   
        foreach ($permissionName as $value) {
            $permission = Permission::where(['name'=>$value])->first();
            $role->attachPermission($permission);
        }

        $this->signIn();

        $this->user->attachRole($role);

        $free = \App\Role::called('free');
        $this->user->detachRole($free);



        return $this;
    }


    public function signInAsPermissionlessUser(){
        $this->signInAsPermissionedUser('role-with-no-permission');
        return $this;
    }



    /**
     * Kind of stupid function that can probably be fixed
     * @return User The user
     */
    public function signInAsSubscriber(){

        $user = createUserWithStripePlan();

        $user->addUserRoleByName('subscriber');

        $this->signIn($user);

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
                base_path("tests/unit/File/$filename"), $filename, $this->files[$filename]['type'], 12000, null, TRUE
            ),
            'description'   => $faker->sentence,
            'user_id' =>        $user->id
        ];
    }

    public function clearLocalDevices(){
        //Clear out local devices
        $devices = Device::where('ip_address','=','127.0.0.1')->get();
        foreach($devices as $device){
            $device->delete();
        }
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

        if($actual==$code){
            $this->assertEquals($actual,$code);
            return $this;
        }


        if($actual!=200){
            $message = "A request to failed to get expected status code. Received status code [{$actual}].";

            $responseException = isset($this->response->exception)
                    ? $this->response->exception : null;

            if ($responseException instanceof \Illuminate\Validation\ValidationException){
                $message .= response()->json($responseException->validator->getMessageBag(), 400);
            }
             
            throw new \Illuminate\Foundation\Testing\HttpException($message, null, $responseException);

            return $this;
        }


        PHPUnit_Framework_TestCase::assertEquals($code, $this->response->getStatusCode(), "Expected status code {$code}, got {$actual}. \nResponseContent:    ". $this->response->getContent());
        return $this;
    }


    ////////// APITestCase Workings


    /**
     * Posts a model with the given fields and checks that the status code matches
     * @param  Array  $fields An array of fields to post
     * @param  integer $code   The code expected
     * @param  String $responseToSee An optional string to look for
     */
    function storeFieldsGetSee($fieldsToPost,$expectedCode=200,$responseToSee="",$jsonFields=[]){
        $this->setUnsetDefaults();

        $contentToPost = $this->getPostArray($this->class,$fieldsToPost);

        $this->post($this->route,$contentToPost)
             ->assertResponseStatus($expectedCode);

        if($responseToSee) $this->see($responseToSee);

        if($expectedCode==200) $this->checkDatabaseFor($contentToPost,$jsonFields);

        return $this;
    }

   /**
     * Posts a model with the required fields merged with any content that user wants to submit
     * @param  Array  $content An array of content to merge into a post
     * @param  integer $code   The code expected
     */
    function storeContentGetSee($contentToPost,$expectedCode=200,$reponseToSee="",$jsonFields=[]){
        $this->setUnsetDefaults();

        $defaultPost = $this->getPostArray($this->class,$this->defaultFields);

        $mergedContentToPost = array_merge($defaultPost, $contentToPost);
       
        $this->post($this->route,$this->removeNullValues($mergedContentToPost))
                ->assertResponseStatus($expectedCode);

        if($expectedCode==200) $this->checkDatabaseFor($contentToPost,$jsonFields);
        
    }

    /**
     * Makes a user and updates them with the fields asked for
     * @param  Array  $fields An array of fields to post
     * @param  integer $code   The code expected
     */
    public function updateFieldsGetSee($fieldsToPost,$expectedCode=200,$reponseToSee="",$jsonFields=[]){
        $this->setUnsetDefaults();

        if(!$this->modelToUpdate){
            $this->modelToUpdate = factory($this->class)->create();
        }

        $contentToPost = $this->getPostArray($this->class,$fieldsToPost);

        $this->patch($this->route.$this->modelToUpdate->id,$contentToPost)
                ->assertResponseStatus($expectedCode);

        $contentToPost['id'] = $this->modelToUpdate->id;

        if($expectedCode==200) $this->checkDatabaseFor($contentToPost,$jsonFields);

    }



   /**
     * Makes a user then merges them in with any content that user wants to submit
     * @param  Array  $content An array of content to merge into a post
     * @param  integer $code   The code expected
     */
    public function updateContentGetSee($contentToPost,$expectedCode=200,$reponseToSee=null,$jsonFields=[]){
        $this->setUnsetDefaults();

        if(!$this->modelToUpdate){
            $this->modelToUpdate = factory($this->class)->create();
        }

        $defaultPost = $this->getPostArray($this->class,$this->defaultFields);

        $mergedContentToPost = array_merge($defaultPost, $contentToPost);

        $this->patch($this->route.$this->modelToUpdate->id,$this->removeNullValues($mergedContentToPost))
                ->assertResponseStatus($expectedCode);
       
        $contentToPost['id'] = $this->modelToUpdate->id;

        if($expectedCode==200) $this->checkDatabaseFor($contentToPost,$jsonFields);

    }


    /**
     * Gets a user post array with given fields
     * @param  Array $fields An array of fields
     * @return Array         An array ready to post
     */
    public function getPostArray($class,$fields){
        $post = factory($class)->make()->setVisible($fields)->toArray();
        
        // Restore always hidden fields
        foreach($this->alwaysHidden as $hiddenField){
            if(in_array($hiddenField,$fields)){
                $post[$hiddenField]   = 'abcd1234!'; //Usually (if not always) password
            }
        }
       

        foreach($post as $postField => $postValue){
            if(!in_array($postField,$fields)){
                unset($post[$postField]);    
            }
        }

        return $post;
    }

    /**
     * Gets an array without the values in the the values array
     * @param  Array $array Array of values
     * @param  Array $value Values to remove
     * @return Array        Array without the values
     */
    public function getArrayWithoutValues($array, $values){
        foreach($values as $value){
            unset($array[$value]);    
        }        
        return $array;
      
    }

    /**
     * Gets all the keys out of the array where the value is null
     * @param  Array $array Array candidate
     * @return Array        Array with null values filtered out
     */
    public function removeNullValues($array){
        return array_filter($array, function($value){
            return $value !== null;
        });  
    }


    /**
     * Looks for any fields in the database and also checks JSON columns
     * @param  Array    $contentPosted Array of key value pairs to check for
     * @param  Array    $jsonFields    Keys of any fields that are JSON
     * @return $this
     */
    
    public function checkDatabaseFor($contentPosted,$jsonFields){

        $nonJsonFields = array_diff_key($contentPosted,array_flip($jsonFields));

        if(!empty($nonJsonFields)) $this->seeInDatabase($this->table,$this->getArrayWithoutValues($nonJsonFields,$this->skipDatabaseCheck));

        foreach($jsonFields as $jsonField){
            $query = $this->class::where('content->'.$jsonField,$contentPosted[$jsonField]);

            if(array_key_exists('id',$contentPosted)){
                $query->where('id',$contentPosted['id']);
            }

            $record = $query->first();

            $this->assertNotEquals($record,null,"Unable to find JSON record for '$jsonField' in the database equal to '$contentPosted[$jsonField]'");
            $this->assertNotEquals($record->$jsonField,null);           
        }
        return $this;
    }

    /**
     * Guessing the things that people should be setting
     */
    public function setUnsetDefaults(){
        if(!isset($this->alwaysHidden)){
            $this->alwaysHidden = []; //Guessing if not set
        }

        if(!isset($this->table)){
            $this->table =(new $this->class)->getTable();
        }

        if(!isset($this->skipDatabaseCheck)){
            $this->skipDatabaseCheck = $this->alwaysHidden;
        }
    }

}
