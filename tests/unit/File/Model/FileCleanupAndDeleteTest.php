<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Article;

use Carbon\Carbon;

class FileCleanupAndDeleteTest extends TestCase
{
   use DatabaseTransactions;

    public function setUp(){
        parent::setUp();
  
        $this->signIn();
        $this->user->addUserRoleByName('administrator');
        $this->setSettings(['paywall.on'=>0]);

    }

  public function tearDown(){
    $this->restoreSettings();
    parent::tearDown();
  }

//*********** CRUD Own Draft Article *******************************/

        

    /** @test **/
    public function deleting_file_removes_from_storage(){

        $file = factory(App\File::class)->create();

        $filename = $file->filename;

        $this->assertEquals(true,Storage::exists($filename));

        $file->delete();

        $this->assertEquals(false,Storage::exists($filename));
    }


    

}


