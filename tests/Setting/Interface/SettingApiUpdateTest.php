<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;


use App\Setting;

class SettingApiUpdateTest extends TestCase
{

    use WithoutMiddleware;

    public function setUp(){
        parent::setUp();

        $faker = \Faker\Factory::create();

        $this->setSettings(['testsetting'=>$faker->word]);
        $this->setSettings(['testnestedsetting.nested'=>$faker->sentence]);

        $this->signInAsRole('administrator');
    }

    /// Sucess Tests

    /** @test */
    public function update_present_key_suceeds()
    {
        $this->patch('/setting/testsetting', ['value' => 'false'])
            ->assertResponseStatus(200);

    }

    /** @test */
    public function update_present_nested_key_suceeds()
    {
        $this->patch('/setting/testnestedsetting', ['key'=>'nested', 'value' => 'false'])
            ->assertResponseStatus(200);
    }


    /// Fail Tests

    /** @test */
    public function update_missing_key_fails()
    {
        $this->put('/api/setting/adadad', ['value' => 'false'])
            ->assertResponseStatus(400);

    }

    /** @test */
    public function update_missing_nested_key_fails()
    {
        $this->put('/api/setting/notnested', ['key'=>'notexist', 'value' => 'false'])
            ->assertResponseStatus(400);

    }


}
