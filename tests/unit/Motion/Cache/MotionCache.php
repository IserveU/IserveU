<?php

use DB;
use Illuminate\Foundation\Testing\DatabaseTransactions;

abstract class MotionCache extends TestCase
{
    use DatabaseTransactions;

    protected $route = '/api/motion/';
    protected $class = App\Motion::class;
    protected $table = 'motions';
    protected $modelToUpdate;
    protected $update = ['title'=>'Updated Motion'];

    public function updateInDB()
    {
        DB::table($this->table)->where(['id'=>$modelToUpdate->id])->update($this->update);
    }
}
