<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\File;

use Carbon\Carbon;

class FileVersioningTest extends TestCase
{

    use DatabaseTransactions;

    public function setUp(){

        parent::setUp();
        $this->signIn();
        $this->user->addUserRoleByName('administrator');
    }


    /**
     * @test
     */
    public function file_resize_defaults_work(){

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
    public function file_resize_defined_work(){

        $motion = factory(App\Motion::class)->create();
        $file = factory(App\File::class)->create();
        $motion->files()->save($file);

          //Can download
        $this->get('/api/motion/'.$motion->slug.'/file/'.$file->slug.'/resize/100/100')
            ->assertResponseStatus(200);

    }

}
