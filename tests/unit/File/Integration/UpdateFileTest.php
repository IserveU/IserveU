<?php

use App\File;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UpdateFileTest extends TestCase
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
     **/
    public function can_update_a_file()
    {
        $page = factory(App\Page::class)->create();
        $existing = factory(App\File::class)->create([
          'title' => 'Existing File',
        ]);

        $page->files()->save($existing);

        $file = $this->getAnUploadedFile();
        $this->patch('/api/page/'.$page->slug.'/file/'.$existing->slug, ['file' => $file, 'title' => 'Replacement Title'])
            ->assertResponseStatus(200)
            ->seeJsonStructure([
                'slug',
                'id',
                'type',
            ])
            ->seeInDatabase('files', ['fileable_id' => $page->id, 'fileable_type' => 'App\\Page', 'title' => 'Replacement Title', 'replacement_id'=>null])
            ->seeInDatabase('files', ['fileable_id' => $page->id, 'fileable_type' => 'App\\Page', 'title' => $existing->title, 'replacement_id'=>$existing->id++]);
    }
}
