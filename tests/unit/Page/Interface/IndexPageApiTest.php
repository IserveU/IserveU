<?php

include_once 'PageApi.php';

use Illuminate\Foundation\Testing\DatabaseTransactions;


class IndexPageApiTest extends PageApi
{
    use DatabaseTransactions;

    protected static $pages;

    public function setUp()
    {
        parent::setUp();

        if (is_null(static::$pages)) {
            static::$pages = factory(App\Page::class, 5)->create();
        }
    }

    ///////////////////////////////////////////////////////////CORRECT RESPONSES

    /** @test */
    public function page_filter_defaults()
    {
        $this->get($this->route)
            ->seeJsonStructure([
                '*' => [
                    'id',
                    'title',
                    'slug',
                    'content',
                    'created_at',
                    'updated_at',
                ],
            ]);
    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES
}
