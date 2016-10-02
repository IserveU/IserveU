<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

abstract class DepartmentApi extends TestCase
{

    protected $route                =   "/api/department/";
    protected $class                =   App\Department::class;
    protected $table                =   "departments";
    protected $alwaysHidden         =   [];
    protected $defaultFields        =   [];
    protected $modelToUpdate;

}