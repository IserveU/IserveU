<?php

namespace App\Http\Controllers\Comment;

use App\Comment;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Comment\DestroyCommentRequest;
use App\Http\Requests\Comment\IndexCommentRequest;
use App\Http\Requests\Comment\ShowCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Vote;
use Carbon\Carbon;
use Illuminate\Support\Facades\Request;
use Validator;

class CommentController extends ApiController
{
    /**
     * Display a listing of comments, be sure to hide the user_id or identifying features if this person is not logged in.
     *
     * @return Response
     */
    public function index(IndexCommentRequest $request)
    {
        $input = Request::all();

        if (!isset($input['start_date'])) {
            $input['start_date'] = Carbon::today();
        }

        if (!isset($input['end_date'])) {
            $input['end_date'] = Carbon::tomorrow();
        }

        if (!isset($input['number'])) {
            $input['number'] = 1;
        }

        $validator = Validator::make($input, [
            'start_date'      => 'date',
            'end_date'        => 'date',
            'number'          => 'integer',
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        $comments = Comment::with('commentvotes', 'vote')
                    ->betweenDates($input['start_date'], $input['end_date'])
                    ->get()
                    ->sortBy('commentRank')->reverse();

        return $comments;
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
