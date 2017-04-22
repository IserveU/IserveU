<?php

use App\File;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UploadFileTest extends BrowserKitTestCase
{
    //  use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
        $this->signIn();
        $this->user->addRole('administrator');
    }

    /**
     * @test
     **/
    public function can_store_file()
    {
        $file = $this->getAnUploadedFile();

        $motion = factory(App\Motion::class)->create();

        $this->post('/api/motion/'.$motion->slug.'/file', ['file' => $file])
            ->assertResponseStatus(200)
            ->seeJsonStructure([
                'slug',
                'id',
                'type',
            ])
            ->seeInDatabase('files', ['fileable_id' => $motion->id, 'fileable_type' => 'App\\Motion']);
    }
}
