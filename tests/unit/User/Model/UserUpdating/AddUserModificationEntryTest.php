<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class AddUserModificationEntryTest extends BrowserKitTestCase
{
    //   use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
    }

    // WILL FAIL AS THE FIELDS ATTRIBUTE IS BLANK IN DB

    /** @test **/
    public function user_attribute_update_get_recorded_in_modification_table()
    {
        $this->signInAsRole('citizen');
        $this->patch('/api/user/'.$this->user->slug, ['first_name' => 'tested'])->assertResponseStatus(200);

        $this->assertContains('\"first_name\":\"tested\"', DB::table('user_modifications')->where('modification_by_id', $this->user->id)->get()->toJson());
    }

    /** @test **/
    public function user_editing_other_user_info_get_recorded_in_modification_table()
    {
        $this->signInAsRole('administrator');
        $user = factory(App\User::class)->create();

        $user->update([
            'phone' => '011899988199',
        ]);

        $this->seeInDatabase('user_modifications', ['modification_by_id' => $this->user->id, 'modification_to_id' => $user->id]);
    }
}
