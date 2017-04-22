<?php

use App\File;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class PageFileIntegrationTest extends BrowserKitTestCase
{
    //use WithoutMiddleware; Needed for the generation of expections

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
    public function file_store_integrated_with_page()
    {
        $filePost = ['file' => $this->getAnUploadedFile()];

        $page = factory(App\Page::class)->create();

        //Can store
        $this->post('/api/page/'.$page->slug.'/file', $filePost)
            ->assertResponseStatus(200)
            ->seeJsonStructure([
                'slug', 'type', 'mime', 'fileable_id', 'fileable_type',
            ])
            ->seeInDatabase('files', ['fileable_id' => $page->id, 'fileable_type' => 'App\\Page', 'replacement_id' => null]);
    }

    /**
     * @test
     */
    public function file_download_integrated_with_page()
    {
        $page = factory(App\Page::class)->create();
        $file = factory(App\File::class)->create();
        $page->files()->save($file);

          //Can download
        $this->get('/api/page/'.$page->slug.'/file/'.$file->slug.'/download')
            ->assertResponseStatus(200);
    }

    /**
     * @test
     */
    public function file_show_integrated_with_page()
    {
        $page = factory(App\Page::class)->create();
        $file = factory(App\File::class)->create();
        $page->files()->save($file);

        //Can show
        $this->get('/api/page/'.$page->slug.'/file/'.$file->slug)
            ->assertResponseStatus(200)
            ->seeJsonStructure([
                'slug', 'title', 'description', 'replacement_id', 'type', 'mime', 'fileable_id', 'fileable_type',
        ]);
    }

    /**
     * @test
     */
    public function file_patch_integrated_with_page()
    {
        $filePost = ['file' => $this->getAnUploadedFile()];

        $page = factory(App\Page::class)->create();
        $file = factory(App\File::class)->create();
        $page->files()->save($file);

         //Can patch
        $this->patch('/api/page/'.$page->slug.'/file/'.$file->slug, $filePost)
            ->assertResponseStatus(200)
            ->seeJsonStructure([
                'slug', 'title', 'description', 'replacement_id', 'type', 'mime', 'fileable_id', 'fileable_type',
            ])
            ->seeInDatabase('files', ['fileable_id' => $page->id, 'fileable_type' => 'App\\Page', 'replacement_id' => $file->id]);
    }

    /**
     * @test
     */
    public function file_delete_integrated_with_page()
    {
        $page = factory(App\Page::class)->create();
        $file = factory(App\File::class)->create();
        $page->files()->save($file);

        //Can cascade delete
        $this->delete('/api/page/'.$page->slug.'/file/'.$file->slug)
            ->assertResponseStatus(200)
            ->seeJsonStructure([
                'slug', 'title', 'description', 'replacement_id', 'type', 'mime', 'fileable_id', 'fileable_type',
            ])
            // The previous file
            ->dontSeeInDatabase('files', ['slug' => $file->slug])
            ->dontSeeInDatabase('files', ['fileable_id' => $page->id, 'fileable_type' => 'App\\Page', 'replacement_id' => $file->id]);
    }
}
