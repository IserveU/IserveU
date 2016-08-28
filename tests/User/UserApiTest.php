<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserApiTest extends TestCase
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

        $preferences = json_encode([
            "ham"           =>  "cheese",
            "icons"         =>  "Awesome Icons"    
        ]);


        $this->patch('/api/user/'.$this->user->id,['preferences'=>$preferences]);

        $this->assertResponseStatus(200);

        $user = $this->user->fresh();

        $this->assertContains("cheese",$user->preferences);
    }

    /** @test  ******************/
    public function can_create_user_with_preferences(){

        $user = factory(App\User::class)->make([

        ]);



        $faker = \Faker\Factory::create();

        $preferences = json_encode([
            "occupation"      =>  "student"
        ]);
     
        $user = factory(App\User::class)->make([
            "preferences"   =>  $preferences
        ])->setVisible(['first_name','last_name','email','password','preferences'])->toArray();

        $user['password'] = $faker->password;

        $this->post('/api/user',$user)
             ->assertResponseStatus(200)
             ->seeJson([
                    'preferences'   =>  [
                        'occupation'    =>  'student'
                    ]
             ]);
    }

}
