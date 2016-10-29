<?php

use App\Role;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeleteUserRoleApiTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->signInAsRole('administrator');
    }

    ///////////////////////////////////////////////////////////CORRECT RESPONSES

    /** @test */
    public function revoke_role_from_user()
    {
        $user = factory(App\User::class)->create();
        $role = Role::first();
        $user->addRole($role);

        $this->delete('/api/user/'.$user->slug.'/role/'.$role->name)
            ->assertResponseStatus(200)
            ->dontSeeInDatabase('role_user', ['user_id' => $user->id, 'role_id' => $role->id]);
    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES

    /** @test */
    public function revoke_role_from_user_it_doesnt_have_fails()
    {
        $user = factory(App\User::class)->create();
        $role = Role::first();
        $this->delete('/api/user/'.$user->slug.'/role/'.$role->name)
            ->assertResponseStatus(400);
    }
}
