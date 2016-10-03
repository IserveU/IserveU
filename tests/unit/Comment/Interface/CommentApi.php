<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

abstract class CommentApi extends TestCase
{

    protected $route                =   "/api/comment/";
    protected $class                =   App\Comment::class;
    protected $table                =   "comments";
    protected $alwaysHidden         =   [];
    protected $defaultFields        =   [];
    protected $modelToUpdate;

}