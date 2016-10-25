<?php

use App\Article;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FileCleanupAndDeleteTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->signIn();
        $this->user->addRole('administrator');
        $this->setSettings(['paywall.on' => 0]);
    }

    public function tearDown()
    {
        $this->restoreSettings();
        parent::tearDown();
    }

//*********** CRUD Own Draft Article *******************************/

    /** @test **/
    public function deleting_file_removes_from_storage()
    {
        $file = factory(App\File::class)->create();

        $filename = $file->filename;

        $this->assertEquals(true, Storage::exists($filename));

        $file->delete();

        $this->assertEquals(false, Storage::exists($filename));
    }

    /**
     * @test
     */
    public function file_delete_cascades_older_versions()
    {
        $fileA = factory(App\File::class)->create();

        $fileB = factory(App\File::class)->create([
            'replacement_id'    => $fileA->id,
        ]);

        $fileA->delete();

        $this->dontSeeInDatabase('files', ['id' => $fileB->id]);
    }
}
