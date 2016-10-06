<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

abstract class PageApi extends TestCase
{
    use DatabaseTransactions;


    protected $route                =   "/api/page/";
    protected $class                =   App\Page::class;
    protected $table                =   "pages";
    protected $alwaysHidden         =   [];
    protected $defaultFields        =   ['title','department_id'];
    protected $modelToUpdate;

}