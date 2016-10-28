<?php

use App\Setting;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class SettingApiUpdateTest extends TestCase
{
    use WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();

        $faker = \Faker\Factory::create();

        $this->setSettings(['testsetting' => $faker->word]);
        $this->setSettings(['testnestedsetting.nested' => $faker->sentence]);

        $this->signInAsRole('administrator');
    }

    /// Sucess Tests

    /** @test */
    public function update_present_key_suceeds()
    {
        $this->patch('/api/setting/testsetting', ['value' => 'false'])
            ->assertResponseStatus(200);
    }

    /** @test */
    public function update_present_nested_key_suceeds()
    {
        $this->patch('/api/setting/testnestedsetting.nested', ['value' => 'false'])
            ->assertResponseStatus(200);
    }

    /** @test */
    public function update_series_of_keys_suceeds()
    {
        $faker = \Faker\Factory::create();

        $slogan = $faker->sentence;
        $twitter = $faker->url;
        $facebook = $faker->url;

        $this->patch('/api/setting/site.slogan', ['value' => $slogan])
                    ->assertResponseStatus(200);
        $this->patch('/api/setting/site.twitter', ['value' => $twitter])
                    ->assertResponseStatus(200);

        $this->patch('/api/setting/site.facebook', ['value' => $facebook])
            ->assertResponseStatus(200);


        $this->assertEquals(Setting::get('site.slogan'), $slogan);
        $this->assertEquals(Setting::get('site.twitter'), $twitter);
        $this->assertEquals(Setting::get('site.facebook'), $facebook);
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
        $this->put('/api/setting/notnested', ['value' => 'false'])
            ->assertResponseStatus(400);
    }

    /** @test */
    public function update_with_array_key_fails()
    {
        $this->put('/api/setting/blah', ['value' => 'false'])
            ->assertResponseStatus(400);
    }
}
