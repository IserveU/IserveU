<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class UpdatePermissionsTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $faker = \Faker\Factory::create();

        $this->setSettings(['testsetting' => $faker->word]);
    }

    /** @test */
    public function all_guests_redirected_to_login()
    {
        $this->patch('/api/setting/testsetting', ['value' => 'sdfsddf'])
            ->assertResponseStatus(302);
    }

    /** @test */
    public function normal_user_cannot_update()
    {
        $this->signIn();
        $this->patch('/api/setting/testsetting', ['value' => 'thing'])
            ->assertResponseStatus(403);
    }

    /** @test */
    public function user_with_all_permissions_fails()
    {
        $allPermissions = \App\Permission::all()->pluck('name')->toArray();

        $this->signInAsPermissionedUser($allPermissions); //Only the admin ROLE can access this

        $this->patch('/api/setting/testsetting', ['value' => 'false'])
            ->assertResponseStatus(403);
    }

    /** @test */
    public function user_who_is_administrator_can_show_index()
    {
        $this->signInAsAdmin();
        $this->patch('/api/setting/testsetting', ['value' => 'false'])
            ->assertResponseOk();
    }
}
