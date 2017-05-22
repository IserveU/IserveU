<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

abstract class FileApi extends BrowserKitTestCase
{
    use DatabaseTransactions;

    protected $route = '/api/file/';
    protected $class = App\File::class;
    protected $table = 'files';
    protected $alwaysHidden = [];
    protected $defaultFields = ['title', 'department_id'];
    protected $modelToUpdate;

    public function setUp()
    {
        parent::setUp();
        ini_set('memory_limit', '-1');
        $this->parent = factory(App\Motion::class)->create();

        $this->route = '/api/motion/'.$this->parent->slug.'/file/';
    }
}
