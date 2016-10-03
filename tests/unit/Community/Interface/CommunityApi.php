<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

abstract class CommunityApi extends TestCase
{

    protected $route                =   "/api/community/";
    protected $class                =   App\Community::class;
    protected $table                =   "communities";
    protected $alwaysHidden         =   [];
    protected $defaultFields        =   [];
    protected $modelToUpdate;

}