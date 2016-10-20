<?php


abstract class CommentApi extends TestCase
{
    protected $route = '/api/comment/';
    protected $class = App\Comment::class;
    protected $table = 'comments';
    protected $alwaysHidden = [];
    protected $defaultFields = [];
    protected $modelToUpdate;
}
