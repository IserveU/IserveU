<?php

use App\Article;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FileEventsTest extends BrowserKitTestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    //*********** CRUD Own Draft Article *******************************/

    /** @test */
    public function submitting_a_folder_name_creates_a_snakecase_folder()
    {
        $faker = \Faker\Factory::create();

        $wordA = $faker->word;
        $wordB = $faker->word;
        $wordC = ucfirst($faker->word);

        $file = factory(App\File::class)->create([
            'folder' => "$wordA $wordB $wordC",
        ]);

        $this->assertTrue(file_exists(storage_path('app/'.strtolower($wordA)."_".strtolower($wordB).'_'.strtolower($wordC))));
    }
}
