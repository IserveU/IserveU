<?php

use App\File;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MotionFileIntegrationTest extends BrowserKitTestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
        $this->signIn();
        $this->user->addRole('administrator');
    }

    /**
     * @test
     */
    public function file_store_integrated_with_motion()
    {
        $filePost = ['file' => $this->getAnUploadedFile()];

        $motion = factory(App\Motion::class)->create();

        //Can store
        $this->post('/api/motion/'.$motion->slug.'/file', $filePost)
            ->assertResponseStatus(200)
            ->seeJsonStructure([
                'slug', 'type', 'mime', 'fileable_id', 'fileable_type',
            ])
            ->seeInDatabase('files', ['fileable_id' => $motion->id, 'fileable_type' => 'App\\Motion', 'replacement_id' => null]);
    }

    /**
     * @test
     */
    public function file_download_integrated_with_motion()
    {
        $motion = factory(App\Motion::class)->create();
        $file = factory(App\File::class)->create();
        $motion->files()->save($file);

          //Can download
        $this->get('/api/motion/'.$motion->slug.'/file/'.$file->slug.'/download')
            ->assertResponseStatus(200);
    }

    /**
     * @test
     */
    public function file_show_integrated_with_motion()
    {
        $motion = factory(App\Motion::class)->create();
        $file = factory(App\File::class)->create();
        $motion->files()->save($file);

        //Can show
        $this->get('/api/motion/'.$motion->slug.'/file/'.$file->slug)
            ->assertResponseStatus(200)
            ->seeJsonStructure([
                'slug', 'title', 'description', 'replacement_id', 'type', 'mime', 'fileable_id', 'fileable_type',
        ]);
    }

    /**
     * @test
     */
    public function file_patch_integrated_with_motion()
    {
        $filePost = ['file' => $this->getAnUploadedFile()];

        $motion = factory(App\Motion::class)->create();
        $file = factory(App\File::class)->create();
        $motion->files()->save($file);

         //Can patch
        $this->patch('/api/motion/'.$motion->slug.'/file/'.$file->slug, $filePost)
            ->assertResponseStatus(200)
            ->seeJsonStructure([
                'slug', 'title', 'description', 'replacement_id', 'type', 'mime', 'fileable_id', 'fileable_type',
            ])
            ->seeInDatabase('files', ['fileable_id' => $motion->id, 'fileable_type' => 'App\\Motion', 'replacement_id' => $file->id]);
    }

    /**
     * @test
     */
    public function file_delete_integrated_with_motion()
    {
        $motion = factory(App\Motion::class)->create();
        $file = factory(App\File::class)->create();
        $motion->files()->save($file);

        //Can cascade delete
        $this->delete('/api/motion/'.$motion->slug.'/file/'.$file->slug)
            ->assertResponseStatus(200)
            ->seeJsonStructure([
                'slug', 'title', 'description', 'replacement_id', 'type', 'mime', 'fileable_id', 'fileable_type',
            ])
            // The previous file
            ->dontSeeInDatabase('files', ['slug' => $file->slug])
            ->dontSeeInDatabase('files', ['fileable_id' => $motion->id, 'fileable_type' => 'App\\Motion', 'replacement_id' => $file->id]);
    }
}
