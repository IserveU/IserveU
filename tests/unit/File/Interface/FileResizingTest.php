<?php

use App\File;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FileResizingTest extends BrowserKitTestCase
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
    public function file_resize_defaults_work()
    {
        $motion = factory(App\Motion::class)->create();
        $file = factory(App\File::class)->create();
        $motion->files()->save($file);

        //Can download
        $this->get('/api/motion/'.$motion->slug.'/file/'.$file->slug.'/resize')
            ->assertResponseStatus(200);
    }

    /**
     * @test
     */
    public function file_resize_defined_work()
    {
        $motion = factory(App\Motion::class)->create();
        $file = factory(App\File::class)->create();
        $motion->files()->save($file);

        //Can download
        $this->get('/api/motion/'.$motion->slug.'/file/'.$file->slug.'/resize/100/100')
            ->assertResponseStatus(200);
    }
}
