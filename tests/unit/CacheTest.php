<?php

use App\Filters\MotionFilter;
use Illuminate\Database\Eloquent\Model;

trait CacheTest
{
    public function updateInDB($model)
    {
        DB::table($this->table)->where(['id' => $model->id])->update($this->update);
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

    public function getFilterCache($query = 20, $userSensitive = false)
    {
        //Will need to move this into a "generate cache key" function of some sort
        $filters = (new MotionFilter())->cacheKey($query, $userSensitive);

        return Cache::tags([str_singular($this->table), str_singular($this->table).'.filters'])->get($filters);
    }

    public function getOtherCache()
    {
        return $this->getModelCache($this->otherModel);
    }

    public function getThisCache()
    {
        return $this->getModelCache($this->thisModel);
    }

    /**
     * Gets the model cache for a model (as opposed to API/croute).
     *
     * @param Model $model
     *
     * @return void
     */
    private function getModelCache(Model $model)
    {
        $slug = $model->slug ?? $model->id;

        return Cache::tags([str_singular($this->table),  str_singular($this->table).'.model'])->get($slug);
    }
}
