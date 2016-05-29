<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserApi extends TestCase
{
   // use DatabaseTransactions;    
    use WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();
    }


    /** @test  ******************/
    public function user_can_update_own_preferences(){
        $this->signIn();

        $faker = \Faker\Factory::create();

        $preferences = [
            "religion"      =>  $faker->word,
            "icons"         =>  "Awesome Icons"    
        ];

        $this->patch('/api/user/'.$this->user->id,['preferences'=>$preferences]);

        $this->assertResponseStatus(200);

        $user = $this->user->fresh();

        $this->assertContains($preferences['religion'],$user->preferences);
    }

    /** @test  ******************/
    public function can_create_user_with_preferences(){

        $faker = \Faker\Factory::create();

        $preferences = [
            "religion"      =>  $faker->word 
        ];
     
        $user = factory(App\User::class)->make([
            "preferences"   =>  $preferences
        ]);

        $this->post('/api/user',$user->setVisible(['first_name','last_name','email','password','preferences'])->toArray())
             ->seeJson([
                
                    'preferences'   =>  $preferences
                
             ]);
    }

}
