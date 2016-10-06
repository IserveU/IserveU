<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\File;

use Carbon\Carbon;

class UploadFileNormalWayTest extends TestCase
{

  //  use DatabaseTransactions;

    public function setUp(){

        parent::setUp();
        $this->signIn();
        $this->user->addUserRoleByName('administrator');
    }

    /**
     * @test
     **/
    public function can_store_file(){
        
        $file = $this->getAnUploadedFile();

        $motion = factory(App\Motion::class)->create();

        $this->post('/api/motion/'.$motion->slug."/file",['file'=>$file])
            ->assertResponseStatus(200)
            ->seeJsonStructure([
                "slug",
                "id",
                "type"
            ])
            ->seeInDatabase('files',['fileable_id'=>$motion->id,"fileable_type"=>"App\\Motion"]);
    }


     /**
     * @test
     **/
    public function can_update_a_file(){

        $motion     = factory(App\Motion::class)->create();
        $existing   = factory(App\File::class)->create();

        $motion->files()->save($existing);

        $file = $this->getAnUploadedFile();
        $this->patch('/api/motion/'.$motion->slug."/file/".$existing->id,['file'=>$file,'title'=>'Replacement Title'])
            ->assertResponseStatus(200)
            ->seeJsonStructure([
                "slug",
                "id",
                "type"
            ])
            ->seeInDatabase('files',['fileable_id'=>$motion->id,"fileable_type"=>"App\\Motion",'title'=>'Replacement Title'])
            ->seeInDatabase('files',['fileable_id'=>$motion->id,"fileable_type"=>"App\\Motion",'title'=>$existing->title]);


    }


}
