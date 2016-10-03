<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;


class IndexPermissionsTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp(){
        parent::setUp();

        $faker = \Faker\Factory::create();

        $this->setSettings(['testsetting'=>$faker->word]);
        $this->setSettings(['testnestedsetting.nested'=>$faker->sentence]);
    }

    /** @test */
    public function guests_not_redirected_to_login(){
        $this->call('GET', '/api/setting');

        // if you're not authenticated you can still see the settings
        $this->assertResponseStatus(200);
    }

    /** @test */
    public function user_can_see_index(){
        $this->signIn();
        $this->get('/api/setting')
            ->assertResponseStatus(200);
    }

    /** @test */
    public function user_with_all_permissions_can_see_index(){
        $allPermissions = \App\Permission::all()->pluck('name')->toArray();

        $this->signInAsPermissionedUser($allPermissions); //Only the admin ROLE can access this

        $this->get('/api/setting')
            ->assertResponseStatus(200);

    }


    /** @test */
    public function user_who_is_administrator_can_show_index()
    {
        $this->signInAsRole('administrator');
        $this->get('/api/setting')
            ->assertResponseStatus(200);

    }


}
