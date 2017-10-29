<?php


abstract class CommentVoteApi extends BrowserKitTestCase
{
    protected $route = '/api/comment_vote/';
    protected $class = App\CommentVote::class;
    protected $table = 'comment_votes';
    protected $alwaysHidden = [];
    protected $defaultFields = [];
    protected $modelToUpdate;

    /**
     * Sets things up so that the currently logged in user has a vote on a motion
     * that has a comment.
     *
     * @return App\Vote The vote for the current user
     */
    public function setupCommentVote()
    {
        // Some other persons comment
        $comment = factory(App\Comment::class)->create();
        $vote = factory(App\Vote::class)->create([
                        'motion_id' => $comment->vote->motion_id,
                        'user_id'   => $this->user->id,
                    ]);

        return $vote;
    }
}
