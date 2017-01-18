<?php


abstract class CommentPermission extends TestCase
{
    protected $route = '/api/comment/';
    protected $class = App\Comment::class;
    protected $table = 'comments';
    protected $alwaysHidden = [];
    protected $defaultFields = [];
    protected $modelToUpdate;

    public function createModel($status, $owner = null)
    {
        $this->modelToUpdate = factory(App\Comment::class)->create([
            'status'  => $status,
        ]);

        if ($owner) {
            $this->modelToUpdate->vote->user_id = $owner->id;
            $this->modelToUpdate->push();
        }

        return $this->modelToUpdate;
    }
}
