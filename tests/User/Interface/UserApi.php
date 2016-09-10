<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

abstract class UserApi extends TestCase
{


    protected $route                =   "/api/user/";
    protected $class                =   App\User::class;
    protected $table                =   "users";
    protected $alwaysHidden         =   ['password'];
    protected $defaultFields        =   ['email','password','first_name','last_name'];
    protected $modelToUpdate;


}
