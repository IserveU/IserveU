<?php
include_once('MotionApi.php');

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Carbon\Carbon;

class IndexMotionApiTest extends MotionApi
{
    use DatabaseTransactions;    

    protected static $motions;

    public function setUp()
    {
        parent::setUp();

        if(is_null(static::$motions)){
            static::$motions =   factory(App\Motion::class,25)->create();

        }
        factory(App\Motion::class,5)->create();
    }



    ///////////////////////////////////////////////////////////CORRECT RESPONSES 

    /** @test */
    public function motion_filter_defaults(){
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
                'data'  =>  [
                    "*" => [
                        'id',
                        'title',
                        'summary',
                        'slug',
                        'text',
                        'closing_at',
                        'status',
                        'motionOpenForVoting',
                        'department' => [
                            'id','name'
                        ]
                    ]
                ]
            ]);
    }
 

    /** @test */
    public function motion_filter_by_created_at_ascending(){
        $this->signInAsRole('administrator');
        $this->json('GET',$this->route,['by_created_at'=>'asc'])
                ->assertResponseStatus(200)
                ->seeOrderInTimeField('asc','created_at');
    }


      /** @test */
    public function motion_filter_by_created_at_descending(){
        $this->signInAsRole('administrator');
        $this->json('GET',$this->route,['by_created_at'=>'desc'])
                ->assertResponseStatus(200)
                ->seeOrderInTimeField('desc','created_at');

    }


    /** @test */
    public function motion_filter_by_closing_descending(){

        $this->signInAsRole('administrator');
        $this->json('GET',$this->route,['by_closing_at'=>'desc'])
                ->assertResponseStatus(200)
                ->seeOrderInTimeField('desc','closing_at');
    }


    /** @test */
    public function motion_filter_by_closing_ascending(){

        $this->signInAsRole('administrator');
        $this->json('GET',$this->route,['by_closing_at'=>'asc'])
                ->assertResponseStatus(200)
                ->seeOrderInTimeField('asc','closing_at');

    }


    /** @test */
    public function motion_filter_by_draft_status(){
        $this->signInAsRole('administrator');

        $motion = factory(App\Motion::class,'draft')->create();

        $this->json('GET',$this->route,['status'=>['draft']])
                ->assertResponseStatus(200);
        $motions = json_decode($this->response->getContent());

        $this->assertTrue(($motions->total>0));

        foreach($motions->data as $motion){
            $this->assertEquals($motion->status,'draft');
        }
    }

    /** @test */
    public function motion_filter_by_review_status(){
        $this->signInAsRole('administrator');

        $motion = factory(App\Motion::class,'review')->create();

        $this->json('GET',$this->route,['status'=>['review']])
                ->assertResponseStatus(200);

        $motions = json_decode($this->response->getContent());

        $this->assertTrue(($motions->total>0));

        foreach($motions->data as $motion){
            $this->assertEquals($motion->status,'review');
        }
    }

    /** @test */
    public function motion_filter_by_published_status(){
        $this->signInAsRole('administrator');

        $motion = factory(App\Motion::class,'published')->create();

        $this->json('GET',$this->route,['status'=>['published']])
                ->assertResponseStatus(200);

        $motions = json_decode($this->response->getContent());

        $this->assertTrue(($motions->total>0));

        foreach($motions->data as $motion){
            $this->assertEquals($motion->status,'published');
        }
    }

    /** @test */
    public function motion_filter_by_closed_status(){
        $this->signInAsRole('administrator');

        $motion = factory(App\Motion::class,'closed')->create();

        $this->json('GET',$this->route,['status'=>['closed']])
                ->assertResponseStatus(200);

        $motions = json_decode($this->response->getContent());
     
        $this->assertTrue(($motions->total>0));

        foreach($motions->data as $motion){
            $this->assertEquals($motion->status,'closed');
        }
    }


    /** @test */
    public function motion_filter_rank_greater_than(){
        $this->signInAsRole('administrator');
        
        //Create a vote on a motion greater than 1
        $vote = factory(App\Vote::class)->create([
            'position'  =>  1
        ]);
        
        $this->json('GET',$this->route,['rank_greater_than'=>0])
                ->assertResponseStatus(200);

        $motions = json_decode($this->response->getContent());

        $this->assertTrue(($motions->total>0));

        foreach($motions->data as $motion){
            $this->assertTrue(($motion->rank>=0));
        }
    }


    /** @test */
    public function motion_filter_rank_less_than(){
        $this->signInAsRole('administrator');
        
        //Create a vote on a motion last than 1
        $vote = factory(App\Vote::class)->create([
            'position'  =>  -1
        ]);

        $this->json('GET',$this->route,['rank_less_than'=>0])
                ->assertResponseStatus(200);

        $motions = json_decode($this->response->getContent());
        
        $this->assertTrue(($motions->total>0));

        foreach($motions->data as $motion){

            $this->assertTrue(($motion->rank<=0));
        }
    }


    /** @test */
    public function motion_filter_user_id(){
        $this->signInAsRole('administrator');

        $motion = factory(App\Motion::class)->create([
            'user_id'   =>  $this->user->id
        ]);

        $this->json('GET',$this->route,['user_id'=>$this->user->id])
                ->assertResponseStatus(200);

        $motions = json_decode($this->response->getContent());
    
        $this->assertTrue(($motions->total>0));

        foreach($motions->data as $motion){
            $this->assertEquals($motion->user->id,$this->user->id);
        }
    }

    /** @test */
    public function motion_filter_by_department_id(){
        $this->signInAsRole('administrator');

        $department = \App\Department::first();

        $motion = factory(App\Motion::class)->create([
            'department_id' =>  $department->id            
        ]);

        $this->json('GET',$this->route,['department_id'=>$department->id])
                ->assertResponseStatus(200);

        $motions = json_decode($this->response->getContent());
    
        $this->assertTrue(($motions->total>0));

        foreach($motions->data as $motion){
            $this->assertEquals($department->id,$motion->department->id);
        }
    }


    /////////////////////////////////////////////////////////// INCORRECT RESPONSES
    
}
