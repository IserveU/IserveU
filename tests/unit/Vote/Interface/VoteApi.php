<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

abstract class VoteApi extends TestCase
{
    use DatabaseTransactions;


    protected $route = '/api/vote/';
    protected $class = App\Vote::class;
    protected $table = 'votes';
    protected $alwaysHidden = [];
    protected $defaultFields = ['title', 'department_id'];
    protected $modelToUpdate;
}
