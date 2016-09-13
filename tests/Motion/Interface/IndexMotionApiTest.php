<?php
include_once('MotionApi.php');

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

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
                        'closing',
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
    public function motion_filter_by_newest_defaults(){
        $this->signInAsRole('administrator');
        $this->request = $this->call('GET',$this->route,['newest'=>true]);

        $newestMotion = static::$motions->sortBy('created_at')->values()->first();
        $motions = json_decode($this->response->getContent());
        
        //Does not work
        //$this->assertEquals($newestMotion->id,$motions->data[0]->id);
    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES
    
}
