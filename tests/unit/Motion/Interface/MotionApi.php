<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

abstract class MotionApi extends BrowserKitTestCase
{
    use DatabaseTransactions;

    protected $route = '/api/motion/';
    protected $class = App\Motion::class;
    protected $table = 'motions';
    protected $alwaysHidden = [];
    protected $defaultFields = ['title', 'department_id'];
    protected $modelToUpdate;
}
