<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;


use App\Setting;

class SettingApiIndexTest extends TestCase
{

    use WithoutMiddleware;
    
    public function setUp(){
        parent::setUp();

        $faker = \Faker\Factory::create();

        $this->setSettings(['testsetting'=>$faker->word]);
        $this->setSettings(['testnestedsetting.nested'=>$faker->sentence]);
    }


    /** @test */
    public function index_sees_setting_json()
    {   
        $this->get('/api/setting')
            ->assertResponseStatus(200)
            ->seeJsonEquals(Setting::all());

    }


}
