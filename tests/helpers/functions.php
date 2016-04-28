<?php

    /*****************************************************************
    *
    *    POST Methods called by user to test roles and permissions
    *
    ******************************************************************/

	function postMotion($self, $attributes = [], $expectedCode = 200)
	{

		if(!$self) {
			return factory(App\Motion::class)->create();
		}

		$motion = factory(App\Motion::class)->make([
			'user_id'	=>	$self->user->id
		])->toArray();

	    $attributes = array_merge($attributes, createClosingDate());

		if($attributes) {
			$motion = array_merge($motion, $attributes);
		}
		
		if(isset($self->token)){
			$motion = array_merge($motion, ['token' => $self->token]);
		}

		$response = $self->call('POST', '/api/motion', $motion);

		if($response->getStatusCode()!=$expectedCode){
			dd($response->getContent);	//Dump fails
		}

		$self->assertResponseStatus($expectedCode);

		return App\Motion::find($response->getOriginalContent()['id']); //This was an array
	}

	function postVote($self)
	{
		if(!$self){
			// stuff
		}

		$motion = postMotion($self, ['status' => 2]);

		$vote = factory(App\Vote::class)->make(['motion_id' => $motion->id])->toArray();
		$vote = $self->call('POST', '/api/vote', $vote);

	    $self->assertResponseOk();

		return $vote->getOriginalContent(); //This is an object
	}


	function postComment($self, $attributes = [])
	{
		if(!$self){
			// stuff
		}

		$vote = postVote($self);

	    // Make a comment
	    $comment = factory(App\Comment::class)->make()->toArray();
	    $comment = array_merge($comment, ['vote_id' => $vote->id]);

		if(isset($self->token)){
			$comment = array_merge($comment, ['token' => $self->token]);
		}

	    $comment = $self->call('POST', '/api/comment', $comment);
	    
	    $self->assertResponseOk();

		return $comment->getOriginalContent();
	}


	function postCommentVote($self)
	{
		if(!$self){
			// stuff
		}

		$vote = postVote($self);
		$comment = createComment($vote->id);

	    // Make a comment vote
	    $comment_vote = factory(App\CommentVote::class)->make(['comment_id' => $comment->id, 'vote_id' => $vote->id])->toArray();

		if(isset($self->token)){
			$comment_vote = array_merge($comment_vote, ['token' => $self->token]);
		}

	    $comment_vote = $self->call('POST', '/api/comment_vote', $comment_vote);

		$self->assertResponseOk();

		return $comment_vote->getOriginalContent();
	}

    /*****************************************************************
    *
    *    Create models to test GETTERS and Views
    *
    ******************************************************************/

	/*
	*	Creates a closing date.	
	*
	*	@return array
	*/

	function createClosingDate()
	{
		$closing = new DateTime();
	    
	    return ['closing' => $closing->add(new DateInterval('P7D'))];
	}



	function createComment($voteId)
	{
		return factory(App\Comment::class)->create(['vote_id' => $voteId]);
	}

    /*****************************************************************
    *
    *    Helper functions to clean up tests.
    *
    ******************************************************************/

	function switchVotePosition()
	{
	    $faker  = Faker\Factory::create();

	    $new_position = $faker->shuffle(array(-1, 0, 1));

	    return $new_position[$faker->numberBetween($min = 0, $max = 2)];
	}

	function publishMotion($motion, $user)
	{

		$updated = $user->call('PATCH', '/api/motion/'.$motion->id, ['status' => 2]);

        $updated = $updated->getOriginalContent();

        return $updated;
	}

	function agreeWithMotion($motion, $user)
	{
		$vote = $user->call('POST', '/api/vote/', 
				['motion_id' => $motion->id, 'position' => 1]);

		return $vote->getOriginalContent();
	}

	function disagreeWithMotion($motion, $user)
	{
		$vote = $user->call('POST', '/api/vote/', 
				['motion_id' => $motion->id, 'position' => -1]);

		return $vote->getOriginalContent();
	}

?>