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
			dd($response->getContent());	//Dump fails
		}

		$self->assertResponseStatus($expectedCode);

		return App\Motion::find($response->getOriginalContent()['id']); //This was an array
	}

	function postVote($self)
	{
		if(!$self){
			// stuff
		}

		$motion = factory(App\Motion::class,'published')->create();

		$vote = factory(App\Vote::class)->make(['motion_id' => $motion->id,'user_id'=>$self->user->id])
			->setVisible(['user_id','motion_id','position'])->toArray();
		

		$vote = $self->call('POST', '/api/vote', $vote);
		
	    $self->assertResponseOk();
		return $vote->getOriginalContent(); //This is an object
	}



	function postComment($self, $attributes = [])
	{
		if(!$self){
			// stuff
		}

		$vote = factory(App\Vote::class)->create([
			'user_id'	=>	$self->user->id
		]);

	    // Make a comment
	    $comment = factory(App\Comment::class)->make()->toArray();
	    $comment = array_merge($comment, ['vote_id' => $vote->id]);

	    $response = $self->call('POST','/api/comment', $comment);

	    
		return $response->getOriginalContent();
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

        return $updated->getOriginalContent();

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


	function generateMotions($self){

		if(!$self->user){
			$user = factory(App\User::class)->create();
		} else {
			$user = $self->user;
		}

		$motions['motionDraft'] = factory(App\Motion::class,'draft')->create();
        $motions['motionMyDraft'] = factory(App\Motion::class,'draft')->create([
            'user_id'   => $user->id 
        ]);

        $motions['motionReview']		= factory(App\Motion::class,'review')->create();
        $motions['motionMyReview'] 		= factory(App\Motion::class,'review')->create([
            'user_id'   => $user->id 
        ]);
        $motions['motionPublished'] 	= factory(App\Motion::class,'published')->create();
        $motions['motionMyPublished'] 	= factory(App\Motion::class,'published')->create([
            'user_id'   => $user->id
        ]);
        $motions['motionClosed'] 		= factory(App\Motion::class,'closed')->create();
        $motions['motionMyClosed'] 		= factory(App\Motion::class,'closed')->create([
            'user_id'   => $user->id
        ]);

        return $motions;
	}

	function filterCheck($self,$hasThese,$doesntHaveThese,$filters=[]){
        $self->call('GET', '/api/motion/',array_merge(['limit'=>5000],$filters));

        foreach($hasThese as $motion){
        	$self->see($motion->title);
        }

        foreach($doesntHaveThese as $motion){
        	$self->dontSee($motion->title);
        }

	}

	
?>