<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

abstract class FileApi extends TestCase
{
    use DatabaseTransactions;


    protected $route                =   "/api/file/";
    protected $class                =   App\File::class;
    protected $table                =   "files";
    protected $alwaysHidden         =   [];
    protected $defaultFields        =   ['title','department_id'];
    protected $modelToUpdate;


   public function setUp()
    {
        parent::setUp();


        $this->parent       =   factory(App\Motion::class)->create();

        $this->route        =   "/api/motion/".$this->parent->slug."/file/";
    }



}