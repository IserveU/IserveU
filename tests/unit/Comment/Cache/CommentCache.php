<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

abstract class CommentCache extends TestCase
{
    use DatabaseTransactions;

    protected $route = '/api/comment/';
    protected $class = App\Motion::class;
    protected $table = 'comments';
    protected $otherModel;
    protected $thisModel;
    protected $update = ['text'=>'Whatever. I do what I want'];

    public function updateInDB($model)
    {
        DB::table($this->table)->where(['id'=>$model->id])->update($this->update);
    }

    public function update($model)
    {
        $model->update($this->update);
    }

    public function triggerFilterRoute()
    {
        return $this->get($this->route)->assertResponseStatus(200);
    }

    public function triggerOtherRoute()
    {
        if (!$this->otherModel) {
            dd('Other model not set');
        }

        return $this->getRoute($this->otherModel);
    }

    public function triggerThisRoute()
    {
        if (!$this->thisModel) {
            dd('This model not set');
        }

        return $this->getRoute($this->thisModel);
    }

    private function getRoute($model)
    {
        $slug = $model->slug ?? $model->id;

        return $this->get($this->route.$slug)->assertResponseStatus(200);
    }

    public function getFilterCache($query = 20)
    {
        return Cache::tags([str_singular($this->table), str_singular($this->table).'.filters'])->get($query);
    }

    public function getOtherCache()
    {
        return $this->getCache($this->otherModel);
    }

    public function getThisCache()
    {
        return $this->getCache($this->thisModel);
    }

    private function getCache($model)
    {
        $slug = $model->slug ?? $model->id;

        return Cache::tags([str_singular($this->table),  str_singular($this->table).'.model'])->get($slug);
    }
}
