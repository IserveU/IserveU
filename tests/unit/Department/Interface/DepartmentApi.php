<?php


abstract class DepartmentApi extends TestCase
{
    protected $route = '/api/department/';
    protected $class = App\Department::class;
    protected $table = 'departments';
    protected $alwaysHidden = [];
    protected $defaultFields = [];
    protected $modelToUpdate;
}
