<?php

use App\Setting;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class SettingApiIndexTest extends BrowserKitTestCase
{
    use WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();

        $faker = \Faker\Factory::create();

        $this->setSettings(['testsetting' => $faker->word]);
        $this->setSettings(['testnestedsetting.nested' => $faker->sentence]);
    }

    /** @test */
    public function index_sees_setting_json()
    {
        $this->get('/api/setting')
            ->assertResponseStatus(200)
            ->seeJsonEquals(Setting::all());
    }
}
