<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MotionPermissionTest extends TestCase
{
    use DatabaseTransactions;    
    use WithoutMiddleware;






    /** @test */
    public function it_can_see_a_motion()
    {
        $motion =  factory(App\Motion::class, 'published')->create();

        $this->call('GET', '/api/motion/'.$motion->id);

        $this->assertResponseOk();
        $this->seeJson([ 'id' => $motion->id, 'text' => $motion->text ]);
    }


    /** @test */
    public function it_cannot_see_an_unpublished_motion()
    {   $this->signIn();
        $motion = factory(App\Motion::class, 'draft')->create();

        $response = $this->call('GET', '/api/motion/'.$motion->id);


        $this->assertEquals(403, $response->status());
    }

    /** @test */
    public function it_can_create_a_motion()
    {
        $this->signInAsPermissionedUser('create-motion');
        $motion  = postMotion($this);

        $this->seeInDatabase('motions', ['title' => $motion->title, 'summary' => $motion->summary]);

        $this->call('GET', '/api/motion/'.$motion->id);
        
        $this->assertResponseOk();
    }


    /** @test */
    public function it_cannot_create_a_motion()
    {
        $draft = factory(App\Motion::class, 'draft')->make()->toArray();
        $review = factory(App\Motion::class, 'review')->make()->toArray();

        $response = $this->call('POST', '/api/motion', $draft);
        $this->assertEquals(302, $response->status());

        $response = $this->call('POST', '/api/motion', $review);
        $this->assertEquals(302, $response->status());

        $this->signIn(); //No Role
        $response = $this->call('POST', '/api/motion', $draft);
        $this->assertEquals(403, $response->status());

        $response = $this->call('POST', '/api/motion', $review);
        $this->assertEquals(403, $response->status());
    }

    /** @test */
    public function it_can_see_a_closed_motion()
    {
        $motion = factory(App\Motion::class,'closed')->create();

        $response = $this->call('GET', '/api/motion/'.$motion->id);

        $this->assertResponseOk();

        $this->seeJson( [ 'id' => $motion->id, 'text' => $motion->text ] );
    }


    /** @test */
    public function it_cannot_create_a_draft_motion_for_another_user()
    {
        $this->signInAsPermissionedUser('create-motion');

        $motion = factory(App\Motion::class,'draft')->make()->toArray();

        $this->post('/api/motion/',$motion);

        $this->assertResponseStatus(403);

        $this->dontSeeInDatabase('motions',array('title'=>$motion['title']));

    }


    /** @test */
    public function it_can_create_a_draft_motion()
    {
        $this->signInAsPermissionedUser('create-motion');

        $motion = factory(App\Motion::class,'draft')->make([
            'user_id'   =>  $this->user->id
        ])->toArray();

        $this->post('/api/motion/',$motion);

        $this->assertResponseStatus(200);

        $this->seeInDatabase('motions',array('title'=>$motion['title']));
    }


    /** @test */
    public function it_cannot_create_a_published_motion()
    {
        $this->signInAsPermissionedUser('create-motion');

        $motion = factory(App\Motion::class,'published')->make()->toArray();

        $this->post('/api/motion/',$motion);

        $this->assertResponseStatus(403);        
    }

    /** @test */
    public function it_cannot_publish_a_draft_motion()
    {
        $this->signInAsPermissionedUser('create-motion');

        $motion = factory(App\Motion::class,'draft')->create([
            'user_id'   => $this->user->id
        ])->toArray();

        $motion['status'] = 2;

        $this->patch('/api/motion/'.$motion['id'],$motion);

        $this->assertResponseStatus(403);        
    }


    /** @test */
    public function it_cannot_publish_a_review_motion()
    {
        $this->signInAsPermissionedUser('create-motion');

        $motion = factory(App\Motion::class,'review')->create([
            'user_id'   => $this->user->id
        ])->toArray();

        $motion['status'] = 2;

        $this->patch('/api/motion/'.$motion['id'],$motion);

        $this->assertResponseStatus(403);        
    }

    /** @test */
    public function it_can_submit_a_draft_for_review()
    {
        $this->signInAsPermissionedUser('create-motion');

        $motion = factory(App\Motion::class,'draft')->create([
            'user_id'   => $this->user->id
        ])->toArray();

        $motion['status'] = 1;

        $this->patch('/api/motion/'.$motion['id'],$motion);

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
    
        $updated = factory(App\Motion::class)->create()->toArray();

        // Create new Closing Date
        $updated = array_merge($updated, createClosingDate() );

        $this->call('PATCH', '/api/motion/'.$updated['id'], $updated);

        $this->assertResponseOk();

        $this->seeInDatabase('motions', ['title' => $updated['title'], 'summary' => $updated['summary'], 'closing' => $updated['closing']]);
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
        $this->seeInDatabase('motions', ['deleted_at' => null]);
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
        $motion  = factory(App\Motion::class,'published')->create();
        $this->signInAsPermissionedUser('delete-motion');
        
        // Delete Motion
        $this->call('DELETE', '/api/motion/'.$motion->id);
        $this->assertResponseOk();
        $this->notSeeInDatabase('motions', ['id'=>$motion->id, 'deleted_at' => null ]);

        // Restore motion
        $this->call('GET', '/api/motion/'.$motion->id.'/restore');
        $this->assertResponseOk();
        $this->seeInDatabase('motions', ['id'=>$motion->id, 'deleted_at' => null]);
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
