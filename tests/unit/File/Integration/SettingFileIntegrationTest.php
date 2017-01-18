<?php

use App\File;
use App\Page;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SettingFileIntegrationTest extends TestCase
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
    public function can_update_site_logo()
    {
        $filePost = ['file' => $this->getAnUploadedFile()];

        $homePage = Page::find(1);
        $logoFile = File::findBySlug('logo-png');

       //Can patch
      $this->patch('/api/page/'.$homePage->id.'/file/logo-png', $filePost)
          ->assertResponseStatus(200)
          ->seeInDatabase('files', ['slug' => 'logo-png', 'replacement_id'=>null])
          ->dontSeeInDatabase('files', ['slug' => 'logo-png', 'filename'=>$logoFile->filename])
          ->seeInDatabase('files', ['filename'=>$logoFile->filename]);
    }

    /**
     * @test
     */
    public function can_update_site_symbol()
    {
        $filePost = ['file' => $this->getAnUploadedFile()];

        $homePage = Page::find(1);
        $symbolFile = File::findBySlug('symbol-png');

       //Can patch
      $this->patch('/api/page/'.$homePage->id.'/file/symbol-png', $filePost)
          ->assertResponseStatus(200)
          ->seeInDatabase('files', ['slug' => 'symbol-png', 'replacement_id'=>null])
          ->dontSeeInDatabase('files', ['slug' => 'symbol-png', 'filename'=>$symbolFile->filename])
          ->seeInDatabase('files', ['filename'=>$symbolFile->filename]);
    }
}
