<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

abstract class UserApi extends TestCase
{
    use DatabaseTransactions;


    /**
     * Posts a user with the given fields and checks that the status code matches
     * @param  Array  $fields An array of fields to post
     * @param  integer $code   The code expected
     */
    public function storeUserWithFields($fields,$code=200){

        $userPost = $this->getUserPostArray($fields);

        $this->post('/api/user/',$userPost)
                ->assertResponseStatus($code);

        if($code==200) $this->seeInDatabase('users',$this->getArrayWithoutValue($userPost,'password'));
    }

   /**
     * Posts a user with the required fields merged with any content that user wants to submit
     * @param  Array  $content An array of content to merge into a post
     * @param  integer $code   The code expected
     */
    public function storeUserWithContent($content,$code=200){

        $userPost = $this->getUserPostArray(['email','password','first_name','last_name']);

        $mergedUserPost = array_merge($userPost, $content);

        $this->post('/api/user/',$this->removeNullValues($mergedUserPost))
                ->assertResponseStatus($code);

        if($code==200) $this->seeInDatabase('users',$this->getArrayWithoutValue($mergedUserPost,'password'));
    }


    /**
     * Makes a user and updates them with the fields asked for
     * @param  Array  $fields An array of fields to post
     * @param  integer $code   The code expected
     */
    public function updateUserWithFields($fields,$code=200){

        $userPost = $this->getUserPostArray($fields);

        $this->signIn();


        $this->patch('/api/user/'.$this->user->id,$userPost)
                ->assertResponseStatus($code);

        $userPost['id'] = $this->user->id;

        if($code==200) $this->seeInDatabase('users',$this->getArrayWithoutValue($userPost,'password'));
    }

   /**
     * Makes a user then merges them in with any content that user wants to submit
     * @param  Array  $content An array of content to merge into a post
     * @param  integer $code   The code expected
     */
    public function updateUserWithContent($content,$code=200){

        $userPost = $this->getUserPostArray(['email','password','first_name','last_name']);

        $this->signIn();

        $mergedUserPost = array_merge($userPost, $content);

        $this->patch('/api/user/'.$this->user->id,$this->removeNullValues($mergedUserPost))
                ->assertResponseStatus($code);

        if($code==200) $this->seeInDatabase('users',$this->getArrayWithoutValue($mergedUserPost,'password'));
    }



    /// Utility Functions

    /**
     * Gets a user post array with given fields
     * @param  Array $fields An array of fields
     * @return Array         An array ready to post
     */
    public function getUserPostArray($fields){
        $userPost = factory(App\User::class)->make()->setVisible($fields)->toArray();
        
        if(in_array( "password" ,$fields )){
            $userPost['password']   = 'abcd1234!';
        }
    
        
        return $userPost;
    }

    /**
     * Gets an array without the 
     * @param  [type] $array [description]
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public function getArrayWithoutValue($array, $value){
        unset($array[$value]);
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

}
