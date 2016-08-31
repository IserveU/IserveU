<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

abstract class UserApiTests extends TestCase
{
    use DatabaseTransactions;

    public function storeUserWithFields($fields,$code=200){

        $userPost = factory(App\User::class)->make()->setVisible(array_merge(['email','first_name','last_name'],$fields))->toArray();

        $userPost['password']   = 'abcd1234!';

        $withoutPassword = array_filter($userPost, function($key){
            return $key != "password";
        },ARRAY_FILTER_USE_KEY);

        $this->post('/api/user/',$userPost)
                ->assertResponseStatus($code)
                ->seeInDatabase('users',$withoutPassword);
    }

}
