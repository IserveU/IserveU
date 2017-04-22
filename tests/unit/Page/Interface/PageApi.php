<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

abstract class PageApi extends BrowserKitTestCase
{
    use DatabaseTransactions;

    protected $route = '/api/page/';
    protected $class = App\Page::class;
    protected $table = 'pages';
    protected $alwaysHidden = [];
    protected $defaultFields = ['title', 'department_id'];
    protected $modelToUpdate;
}
