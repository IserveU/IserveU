<?php

include_once 'MotionApi.php';

class IndexMotionApiTest extends MotionApi
{
    //protected static $motions;

    public function setUp()
    {
        parent::setUp();
        $this->signInAsAdmin();
        // if (is_null(static::$motions)) {
        //     static::$motions = factory(App\Motion::class, 25)->create();
        // }
        factory(App\Motion::class, 2)->create(['status'=> 'published']);
    }

    ///////////////////////////////////////////////////////////CORRECT RESPONSES

    /** @test */
    public function motion_filter_defaults()
    {
        $this->get($this->route)
            ->seeJsonStructure([
                'total',
                'per_page',
                'current_page',
                'last_page',
                'next_page_url',
                'prev_page_url',
                'from',
                'to',
                'data'  => [
                    '*' => [
                        'id',
                        'title',
                        'summary',
                        'slug',
                        'text',
                        'implementation',
                        'closing_at',
                        'published_at',
                        'status',
                        'motionOpenForVoting',
                        'department' => [
                            'id', 'name',
                        ],
                    ],
                ],
            ]);

        $this->seeOrderInTimeField('desc', 'published_at'); //Default order
        $this->dontSee('draft');
        $this->dontSee('review');
    }

    /** @test */
    public function motion_filter_by_created_at_ascending()
    {
        $this->json('GET', $this->route, ['orderBy' => ['created_at'=>'asc']])
                ->assertResponseStatus(200)
                ->seeOrderInTimeField('asc', 'created_at');
    }

    /** @test */
    public function motion_filter_by_created_at_descending()
    {
        $this->json('GET', $this->route, ['orderBy' => ['created_at'=>'desc']])
                ->assertResponseStatus(200)
                ->seeOrderInTimeField('desc', 'created_at');
    }

    /** @test */
    public function motion_filter_by_closing_descending()
    {
        $this->json('GET', $this->route, ['orderBy' => ['closing_at'=>'desc']])
                ->assertResponseStatus(200)
                ->seeOrderInTimeField('desc', 'closing_at');
    }

    /** @test */
    public function motion_filter_by_closing_ascending()
    {
        $this->json('GET', $this->route, ['orderBy' => ['closing_at'=>'asc']])
                ->assertResponseStatus(200)
                ->seeOrderInTimeField('asc', 'closing_at');
    }

    /** @test */
    public function motion_filter_by_draft_status()
    {
        $motion = factory(App\Motion::class, 'draft')->create();

        $this->json('GET', $this->route, ['status' => ['draft']])
                ->assertResponseStatus(200);
        $motions = json_decode($this->response->getContent());

        $this->assertTrue(($motions->total > 0));

        foreach ($motions->data as $motion) {
            $this->assertEquals($motion->status, 'draft');
        }
    }

    /** @test */
    public function motion_filter_by_review_status()
    {
        $motion = factory(App\Motion::class, 'review')->create();

        $this->json('GET', $this->route, ['status' => ['review']])
                ->assertResponseStatus(200);

        $motions = json_decode($this->response->getContent());

        $this->assertTrue(($motions->total > 0));

        foreach ($motions->data as $motion) {
            $this->assertEquals($motion->status, 'review');
        }
    }

    /** @test */
    public function motion_filter_by_published_status()
    {
        $motion = factory(App\Motion::class, 'published')->create();

        $this->json('GET', $this->route, ['status' => ['published']])
                ->assertResponseStatus(200);

        $motions = json_decode($this->response->getContent());

        $this->assertTrue(($motions->total > 0));

        foreach ($motions->data as $motion) {
            $this->assertEquals($motion->status, 'published');
        }
    }

    /** @test */
    public function motion_filter_by_closed_status()
    {
        $motion = factory(App\Motion::class, 'closed')->create();

        $this->json('GET', $this->route, ['status' => ['closed']])
                ->assertResponseStatus(200);

        $motions = json_decode($this->response->getContent());

        $this->assertTrue(($motions->total > 0));

        foreach ($motions->data as $motion) {
            $this->assertEquals($motion->status, 'closed');
        }
    }

    /** @test */
    public function motion_filter_by_title()
    {
        $motion = factory(App\Motion::class, 'published')->create(
            ['title'=> 'this is a unique text']);

        $this->json('GET', $this->route, ['title' =>'this is a unique text'])
                ->assertResponseStatus(200);

        $motions = json_decode($this->response->getContent());
        $this->assertTrue(($motions->total > 0));

        foreach ($motions->data as $motion) {
            $this->assertEquals($motion->title, 'this is a unique text');
        }
    }

    /** @test */
    public function motion_filter_rank_greater_than()
    {

        //Create a vote on a motion greater than 1
        $vote = factory(App\Vote::class)->create([
            'position'  => 1,
        ]);

        $this->json('GET', $this->route, ['rankGreaterThan' => 0])
                ->assertResponseStatus(200);

        $motions = json_decode($this->response->getContent());

        $this->assertTrue(($motions->total > 0));

        foreach ($motions->data as $motion) {
            $this->assertTrue(($motion->rank >= 0));
        }
    }

    /** @test */
    public function motion_filter_rank_less_than()
    {

        //Create a vote on a motion last than 1
        $vote = factory(App\Vote::class)->create([
            'position'  => -1,
        ]);

        $this->json('GET', $this->route, ['rankLessThan' => 0])
                ->assertResponseStatus(200);

        $motions = json_decode($this->response->getContent());

        $this->assertTrue(($motions->total > 0));

        foreach ($motions->data as $motion) {
            $this->assertTrue(($motion->rank <= 0));
        }
    }

    /** @test */
    public function motion_filter_user_id()
    {
        $motion = factory(App\Motion::class, 'published')->create([
            'user_id'   => $this->user->id,
        ]);

        $this->json('GET', $this->route, ['userId' => $this->user->id])
                ->assertResponseStatus(200);

        $motions = json_decode($this->response->getContent());

        $this->assertTrue(($motions->total > 0));

        foreach ($motions->data as $motion) {
            $this->assertEquals($motion->user->id, $this->user->id);
        }
    }

    /** @test */
    public function motion_filter_by_department_id()
    {
        $department = \App\Department::first();

        $motion = factory(App\Motion::class, 'published')->create([
            'department_id' => $department->id,
        ]);

        $this->json('GET', $this->route, ['departmentId' => $department->id])
                ->assertResponseStatus(200);

        $motions = json_decode($this->response->getContent());

        $this->assertTrue(($motions->total > 0));

        foreach ($motions->data as $motion) {
            $this->assertEquals($department->id, $motion->department->id);
        }
    }

    /** @test */
    public function motion_filter_by_nonbinding_implementation()
    {
        $motion = factory(App\Motion::class, 3)->create(
            ['status'=> 'published']);

        $this->json('GET', $this->route, ['implementation' => ['non-binding']])
                ->assertResponseStatus(200);
        $motions = json_decode($this->response->getContent());

        $this->assertTrue(($motions->total > 0));

        foreach ($motions->data as $motion) {
            $this->assertEquals($motion->implementation, 'non-binding');
        }
    }

    /** @test */
    public function motion_filter_by_binding_implementation()
    {
        $motion = factory(App\Motion::class, 3)->create(
            ['status'=> 'published']);

        $this->json('GET', $this->route, ['implementation' => ['binding']])
                ->assertResponseStatus(200);
        $motions = json_decode($this->response->getContent());

        $this->assertTrue(($motions->total > 0));

        foreach ($motions->data as $motion) {
            $this->assertEquals($motion->implementation, 'binding');
        }
    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES
}
