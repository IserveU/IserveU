<?php

include_once 'FileApi.php';

use Illuminate\Foundation\Testing\DatabaseTransactions;

class StoreFileApiTest extends FileApi
{
    use DatabaseTransactions;

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES

    /** @test  ******************/
    public function store_file_with_empty_title_fails()
    {
        //Might need to rework the testing system to handle files
    }
}
