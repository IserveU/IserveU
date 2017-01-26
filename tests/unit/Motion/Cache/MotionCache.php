<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

abstract class MotionCache extends TestCase
{
    use DatabaseTransactions, CacheTest;

    protected $route = '/api/motion/';
    protected $class = App\Motion::class;
    protected $table = 'motions';
    protected $otherModel;
    protected $thisModel;
    protected $update = ['summary'=>'Whatever. I do what I want'];
}
