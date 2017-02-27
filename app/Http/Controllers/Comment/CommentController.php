<?php

namespace App\Http\Controllers\Comment;

use App\Comment;
use App\Filters\CommentFilter;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Comment\DestroyCommentRequest;
use App\Http\Requests\Comment\IndexCommentRequest;
use App\Http\Requests\Comment\ShowCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use Cache;

class CommentController extends ApiController
{
    /**
     * Display a listing of comments, be sure to hide the user_id or identifying features if this person is not logged in.
     *
     * @return Response
     */
    public function index(CommentFilter $filters, IndexCommentRequest $request)
    {
        $limit = $request->get('limit') ?: 20;

        return Cache::tags(['comment', 'comment.filters'])->rememberForever($filters->cacheKey($limit), function () use ($filters, $limit) {
            return Comment::filter($filters)->paginate($limit)->toJson();
        });
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show(ShowCommentRequest $request, Comment $comment)
    {
        return $comment;
    }

    /**
     * Update the specified resource in storage. If it has been deleted, this will undelete it.
     *
     * @param int $id
     *
     * @return Response
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $comment->update($request->except('token'));

        return $comment;
    }

    /**
     * Remove the specified resource from storage, if run twice it permanently deletes it.
     *
     * @param Comment $comment The comment you want to destroy
     *
     * @return Response
     */
    public function destroy(DestroyCommentRequest $request, Comment  $comment)
    {
        $comment->delete();

        return $comment;
    }
}
