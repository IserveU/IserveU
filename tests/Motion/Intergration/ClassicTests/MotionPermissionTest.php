<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MotionPermissionTest extends TestCase
{
    use DatabaseTransactions;    



    /** @test */
    public function it_can_see_a_motion()
    {

        $motion =  factory(App\Motion::class, 'published')->create();

        $this->get('/api/motion/'.$motion->id)
            ->assertResponseStatus(200);

        $this->seeJson([ 'id' => $motion->id, 'text' => $motion->text ]);
    }


    /** @test */
    public function it_cannot_see_an_unpublished_motion()
    {   
        $this->signIn();
        $motion = factory(App\Motion::class, 'draft')->create();

        $response = $this->call('GET', '/api/motion/'.$motion->id);


        $this->assertEquals(403, $response->status());
    }

   

    /** @test */
    public function it_cannot_create_a_motion()
    {
        $this->setSettings(['security.verify_citizens'=>1]);

        $draft = factory(App\Motion::class, 'draft')->make()->setVisible(['title','summary'])->toArray();
        $review = factory(App\Motion::class, 'review')->make()->setVisible(['title','summary'])->toArray();

       $this->json('post','/api/motion', $draft)
            ->assertResponseStatus(401);

       $this->post('/api/motion', $draft) //If hitting this route, redirect to login
            ->assertResponseStatus(302);

       $this->json('post','/api/motion', $review)
            ->assertResponseStatus(401);

       $this->post('/api/motion', $review) //If hitting this route, redirect to login
            ->assertResponseStatus(302);

        $this->signIn(); //No Role

        $this->post('/api/motion', $review)
            ->assertResponseStatus(403);

        $this->post('/api/motion', $review)
            ->assertResponseStatus(403);
    }

    /** @test */
    public function it_can_see_a_closed_motion()
    {

        $motion = factory(App\Motion::class,'closed')->create();

        $response = $this->get('/api/motion/'.$motion->id);

        $this->assertResponseOk();

        $this->seeJson( [ 'id' => $motion->id, 'text' => $motion->text ] );
    }


    /** @test */
    public function it_cannot_create_a_draft_motion_for_another_user()
    {
        $this->signInAsPermissionedUser('create-motion');

        $user = factory(App\Motion::class,'draft')->create();

        $this->post('/api/motion/',['status'=>'draft','title'=>"The title","user_id"=>$user->id])
            ->assertResponseStatus(403);

        $this->dontSeeInDatabase('motions',array('title'=>'The title','user_id'=>$user->id));

    }


    /** @test */
    public function it_can_create_a_draft_motion()
    {
        $this->signInAsPermissionedUser('create-motion');

        $motion['status'] = 'draft';
        $motion['title'] = 'Who cares about these old tests?';
        $motion['user_id'] = $this->user->id;

        $this->post('/api/motion/',$motion);

        $this->assertResponseStatus(200);

        $this->seeInDatabase('motions',array('title'=>$motion['title']));
    }


    /** @test */
    public function it_cannot_create_a_published_motion()
    {
        $this->signInAsPermissionedUser('create-motion');

        $motion['status']   = 'published';
        $motion['title']    = 'These old test suck!';

        $this->post('/api/motion/',$motion);

        $this->assertResponseStatus(403);        
    }

    /** @test */
    public function it_cannot_publish_a_draft_motion()
    {
        $this->signInAsPermissionedUser('create-motion');

        $motion = factory(App\Motion::class,'draft')->create([
            'user_id'   => $this->user->id
        ])->skipVisibility()->setVisible(['title','id'])->toArray();

        $motion['status'] = "published"; //Publish it
        
        $this->patch('/api/motion/'.$motion['id'],$motion);

        $this->assertResponseStatus(403);        
    }


    /** @test */
    public function it_cannot_publish_a_review_motion()
    {
        $this->signInAsPermissionedUser('create-motion');

        $motion = factory(App\Motion::class,'review')->create([
            'user_id'   => $this->user->id
        ])->skipVisibility()->setVisible(['title','id'])->toArray();

        $motion['status'] = "published";

        $this->patch('/api/motion/'.$motion['id'],$motion);

        $this->assertResponseStatus(403);        
    }

    /** @test */
    public function it_can_submit_a_draft_for_review()
    {
        $this->signInAsPermissionedUser('create-motion');

        $motion = factory(App\Motion::class,'draft')->create([
            'user_id'   => $this->user->id
        ]);

        $motionPatch['status'] = 'review';

        $this->patch('/api/motion/'.$motion->id,$motionPatch);

        $this->assertResponseStatus(200);        
    }


    /** @test */
    public function it_can_see_own_draft_motion()
    {
        $this->signIn();

        $motion = factory(App\Motion::class,'draft')->create([
            'user_id'   =>  $this->user->id
        ]);

        $this->get('/api/motion/'.$motion->id);

        $this->assertResponseStatus(200);
    }

    /** @test */
    public function it_can_update_a_closing_date_motion()
    {   

        $this->signInAsPermissionedUser('administrate-motion');
    
        $toUpdate = factory(App\Motion::class)->create();

        // Create new Closing Date
        $updated = createClosingDate();

        $this->patch('/api/motion/'.$toUpdate->id, $updated)
            ->assertResponseStatus(200);

        $this->seeInDatabase('motions', ['title' => $toUpdate->title, 'closing' => $updated['closing']]);
    }

    /** @test */
    public function it_cannot_delete_another_persons_motion()
    {
        $this->signInAsPermissionedUser('create-motion');
        $motion  = factory(App\Motion::class)->create();
        
        // Delete Motion
        $response = $this->call('DELETE', '/api/motion/'.$motion->id);
        $this->assertResponseStatus(403);
        $this->seeInDatabase('motions', ['id' => $motion->id, 'deleted_at' => null]);
    }

    /** @test */
    public function it_cannot_restore_a_motion()
    {
        $this->signInAsPermissionedUser('create-motion');

        $motion  = factory(App\Motion::class)->create();
        $motion->delete();

        // Restore motion
        $this->call('GET', '/api/motion/'.$motion->id.'/restore');
        $this->assertResponseStatus(401);
        $this->dontSeeInDatabase('motions', ['id'=>$motion->id,'deleted_at' => null]);
    }

    /** @test */
    public function it_can_delete_a_motion()
    {
        $this->signInAsPermissionedUser('delete-motion');

        $motion  = factory(App\Motion::class,'published')->create();
        
        // Delete Motion
        $response = $this->call('DELETE', '/api/motion/'.$motion->id);
        $this->assertResponseOk();
 
        $this->notSeeInDatabase('motions', ['id'=>$motion->id, 'deleted_at' => null ]);
    }

    /** @test */ 
    public function it_can_restore_a_motion()
    {
        $vote  = factory(App\Vote::class)->create(); //Or it will do a perma delete

        $this->signInAsPermissionedUser('delete-motion');
        
        // Delete Motion
        $this->call('DELETE', '/api/motion/'.$vote->motion->id);
        $this->assertResponseStatus(200);
        $this->notSeeInDatabase('motions', ['id'=>$vote->motion->id, 'deleted_at' => null ]);

        // Restore motion
        $this->call('GET', '/api/motion/'.$vote->motion->id.'/restore');
        $this->assertResponseOk();
        $this->seeInDatabase('motions', ['id'=>$vote->motion->id, 'deleted_at' => null]);
    }


        /** @test */
    public function update_a_voted_on_motion_title()
    {       
        $this->signInAsPermissionedUser('administrate-motion');
        $faker = \Faker\Factory::create();
        $motion = $vote = factory(App\Vote::class)->create()->motion;

        $newTitle = $faker->word." ".$faker->word." ".$faker->word;

        $this->patch('/api/motion/'.$motion->id,['title'=>$newTitle]);
        $this->assertResponseStatus(200);

    }


    /*****************************************************************
    *
    *                          For Ike:
    *  - Fulfill the conditions of the function names and anything
    *    else that comes up.
    *
    ******************************************************************/


    // /** @test */
    // public function it_cannot_create_or_update_a_comment_without_having_voted()
    // {

    // }

    // /** @test */
    // public function it_cannot_create_or_update_a_comment_vote_without_having_voted()
    // {

    // }

    // /** @test */
    // public function it_cannot_create_a_motion()
    // {

    // }

    // /** @test */
    // public function it_cannot_update_a_motion()
    // {

    // }

    // /** @test */
    // public function it_cannot_delete_a_motion()
    // {

    // }
    // /** @test */
    // public function it_cannot_see_an_unpublished_motion()
    // {

    // }

    // /** @test */
    // public function it_cannot_see_a_private_users_details()
    // {

    // }

    // * @test 
    // public function it_can_see_a_public_users_details()
    // {

    // }

    // /** @test */
    // public function it_can_see_its_own_details()
    // {

    // }

    // /** @test */
    // public function it_can_update_its_own_details()
    // {

    // }

    // /** @test */
    // public function it_can_see_its_updated_details()
    // {

    // }

    // /** @test */
    // public function it_cannot_update_another_users_details()
    // {

    // }

    // /** @test */
    // public function it_cannot_assign_permissions_or_roles()
    // {

    // }

    // /** @test */
    // public function it_cannot_upload_a_background_image()
    // {

    // }

    // /** @test */
    // public function it_cannot_update_the_deparments()
    // {

    // }

    // /** @test */
    // public function it_cannot_update_the_ethnic_origins()
    // {

    // }

    // /** @test */
    // public function it_cannot_create_a_motion_file()
    // {

    // }




    /*****************************************************************
    *
    *                          For Ike:
    *
    *   - fulfill the conditions of the function names and anything else
    *     you can think of!
    *
    *
    ******************************************************************/

    // /** @test */
    // public function it_cannot_create_a_motion_file()
    // {

    // }

    // /** @test */
    // public function it_cannot_assign_permissions_or_roles()
    // {

    // }

    // /** @test */
    // public function it_cannot_upload_a_background_image()
    // {

    // }

    // /** @test */
    // public function it_cannot_update_the_deparments()
    // {

    // }

    // /** @test */
    // public function it_cannot_update_the_ethnic_origins()
    // {

    // }

}
