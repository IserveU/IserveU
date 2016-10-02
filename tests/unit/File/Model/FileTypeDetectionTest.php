<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Article;

use Carbon\Carbon;

class FileTypeDetectionTest extends TestCase
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

        $file = factory(App\File::class,'mp3')->create();

        $filename = $file->filename;

        $this->assertEquals(true,Storage::exists($filename));

        $file->delete();

        $this->assertEquals(false,Storage::exists($filename));
    }


    /** @test **/
    public function file_cleanup_tool_marks_unassociated_files(){
        
        $file = factory(App\File::class)->create();

        $filename = $file->filename;

        $this->assertEquals(true,Storage::exists($filename));


              
    }

    
    /** @test **/
    public function file_detects_image_type(){
        $file1 = factory(App\File::class,'image')->create();
        $this->seeInDatabase('files',['id'=>$file1->id,'mime'=>'jpeg','type'=>'image']); 

        $file11 = factory(App\File::class,'gif')->create();
        $this->seeInDatabase('files',['id'=>$file11->id,'mime'=>'gif','type'=>'image']); 
        $file12 = factory(App\File::class,'bmp')->create();
        $this->seeInDatabase('files',['id'=>$file12->id,'mime'=>'x-ms-bmp','type'=>'image']); 
    }

        /** @test **/
    public function file_detects_document_type(){
        $file2 = factory(App\File::class,'pdf')->create();
        $this->seeInDatabase('files',['id'=>$file2->id,'mime'=>'pdf','type'=>'document']); 
        $file3 = factory(App\File::class,'xls')->create();
        $this->seeInDatabase('files',['id'=>$file3->id,'mime'=>'vnd.ms-excel','type'=>'document']); 
        $file4 = factory(App\File::class,'xlsx')->create();
        $this->seeInDatabase('files',['id'=>$file4->id,'mime'=>'vnd.openxmlformats-officedocument.spreadsheetml.sheet','type'=>'document']); 
        $file5 = factory(App\File::class,'ppt')->create();
        $this->seeInDatabase('files',['id'=>$file5->id,'mime'=>'vnd.ms-powerpoint','type'=>'document']); 
        $file6 = factory(App\File::class,'pptx')->create();
        $this->seeInDatabase('files',['id'=>$file6->id,'mime'=>'vnd.openxmlformats-officedocument.presentationml.presentation','type'=>'document']); 
        $file7 = factory(App\File::class,'doc')->create();
        $this->seeInDatabase('files',['id'=>$file7->id,'mime'=>'msword','type'=>'document']); 
        $file8 = factory(App\File::class,'docx')->create();
        $this->seeInDatabase('files',['id'=>$file8->id,'mime'=>'vnd.openxmlformats-officedocument.wordprocessingml.document','type'=>'document']); 
    }

    /** @test **/
    public function file_detects_archive_type(){
        $file9 = factory(App\File::class,'rar')->create();
        $this->seeInDatabase('files',['id'=>$file9->id,'mime'=>'x-rar','type'=>'archive']); 
        $file10 = factory(App\File::class,'zip')->create();
        $this->seeInDatabase('files',['id'=>$file10->id,'mime'=>'zip','type'=>'archive']); 
    }

  
    /** @test **/
    public function file_detects_video_type(){
        $file13 = factory(App\File::class,'avi')->create();
        $this->seeInDatabase('files',['id'=>$file13->id,'mime'=>'x-msvideo','type'=>'video']); 
        $file14 = factory(App\File::class,'flv')->create();

        $this->seeInDatabase('files',['id'=>$file14->id,'mime'=>'x-flv','type'=>'video']); 
        $file15 = factory(App\File::class,'wmv')->create();
        $this->seeInDatabase('files',['id'=>$file15->id,'mime'=>'x-ms-asf','type'=>'video']); 
      
    }

    /** @test */
    public function file_detects_audio_type(){
        $file16 = factory(App\File::class,'mp3')->create();
        $this->seeInDatabase('files',['id'=>$file16->id,'mime'=>'mpeg','type'=>'audio']); 
    }
       
    

}


