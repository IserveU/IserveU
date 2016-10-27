<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

abstract class DepartmentApi extends TestCase
{
    use DatabaseTransactions;


    protected $route = '/api/department/';
    protected $class = App\Department::class;
    protected $table = 'departments';
    protected $alwaysHidden = [];
    protected $defaultFields = [];
    protected $modelToUpdate;
}
