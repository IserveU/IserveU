<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

abstract class AuthenticateApi extends TestCase
{

    protected $route                =   "/Authenticate";
    protected $class                =   App\User::class; //Usually
    protected $table                =   "users";
    protected $alwaysHidden         =   [];
    protected $defaultFields        =   [];
    protected $modelToUpdate;

}