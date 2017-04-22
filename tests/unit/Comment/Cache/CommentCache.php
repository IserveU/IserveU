<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

abstract class CommentCache extends BrowserKitTestCase
{
    use DatabaseTransactions, CacheTest;

    protected $route = '/api/comment/';
    protected $class = App\Comment::class;
    protected $table = 'comments';
    protected $otherModel;
    protected $thisModel;
    protected $update = ['text'=>'Whatever. I do what I want'];
}
