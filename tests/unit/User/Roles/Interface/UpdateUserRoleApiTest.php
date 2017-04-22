<?php

use App\Role;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UpdateUserRoleApiTest extends BrowserKitTestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->signInAsRole('administrator');
    }

    ///////////////////////////////////////////////////////////CORRECT RESPONSES

    /** @test */
    public function grant_user_a_role()
    {
        $user = factory(App\User::class)->create();
        $role = Role::first();
        $this->patch('/api/user/'.$user->slug.'/role/'.$role->name)
            ->assertResponseStatus(200)
            ->seeInDatabase('role_user', ['user_id' => $user->id, 'role_id' => $role->id]);
    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES

    /** @test */
    public function grant_user_a_role_it_has_fails()
    {
        $user = factory(App\User::class)->create();
        $role = Role::first();
        $user->addRole($role);
        $this->patch('/api/user/'.$user->slug.'/role/'.$role->name)
            ->assertResponseStatus(400);
    }
}
