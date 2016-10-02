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
        $this->seeInDatabase('files',['id'=>$file1->id,'type'=>'jpeg','type_category'=>'image']); 

        $file11 = factory(App\File::class,'gif')->create();
        $this->seeInDatabase('files',['id'=>$file11->id,'type'=>'gif','type_category'=>'image']); 
        $file12 = factory(App\File::class,'bmp')->create();
        $this->seeInDatabase('files',['id'=>$file12->id,'type'=>'x-ms-bmp','type_category'=>'image']); 
    }

        /** @test **/
    public function file_detects_document_type(){
        $file2 = factory(App\File::class,'pdf')->create();
        $this->seeInDatabase('files',['id'=>$file2->id,'type'=>'pdf','type_category'=>'document']); 
        $file3 = factory(App\File::class,'xls')->create();
        $this->seeInDatabase('files',['id'=>$file3->id,'type'=>'vnd.ms-excel','type_category'=>'document']); 
        $file4 = factory(App\File::class,'xlsx')->create();
        $this->seeInDatabase('files',['id'=>$file4->id,'type'=>'vnd.openxmlformats-officedocument.spreadsheetml.sheet','type_category'=>'document']); 
        $file5 = factory(App\File::class,'ppt')->create();
        $this->seeInDatabase('files',['id'=>$file5->id,'type'=>'vnd.ms-powerpoint','type_category'=>'document']); 
        $file6 = factory(App\File::class,'pptx')->create();
        $this->seeInDatabase('files',['id'=>$file6->id,'type'=>'vnd.openxmlformats-officedocument.presentationml.presentation','type_category'=>'document']); 
        $file7 = factory(App\File::class,'doc')->create();
        $this->seeInDatabase('files',['id'=>$file7->id,'type'=>'msword','type_category'=>'document']); 
        $file8 = factory(App\File::class,'docx')->create();
        $this->seeInDatabase('files',['id'=>$file8->id,'type'=>'vnd.openxmlformats-officedocument.wordprocessingml.document','type_category'=>'document']); 
    }

    /** @test **/
    public function file_detects_archive_type(){
        $file9 = factory(App\File::class,'rar')->create();
        $this->seeInDatabase('files',['id'=>$file9->id,'type'=>'x-rar','type_category'=>'archive']); 
        $file10 = factory(App\File::class,'zip')->create();
        $this->seeInDatabase('files',['id'=>$file10->id,'type'=>'zip','type_category'=>'archive']); 
    }

  
    /** @test **/
    public function file_detects_video_type(){
        $file13 = factory(App\File::class,'avi')->create();
        $this->seeInDatabase('files',['id'=>$file13->id,'type'=>'x-msvideo','type_category'=>'video']); 
        $file14 = factory(App\File::class,'flv')->create();

        $this->seeInDatabase('files',['id'=>$file14->id,'type'=>'x-flv','type_category'=>'video']); 
        $file15 = factory(App\File::class,'wmv')->create();
        $this->seeInDatabase('files',['id'=>$file15->id,'type'=>'x-ms-asf','type_category'=>'video']); 
      
    }

    /** @test */
    public function file_detects_audio_type(){
        $file16 = factory(App\File::class,'mp3')->create();
        $this->seeInDatabase('files',['id'=>$file16->id,'type'=>'mpeg','type_category'=>'audio']); 
    }
       
    

}


