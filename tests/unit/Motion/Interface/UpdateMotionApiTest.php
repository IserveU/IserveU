<?php

include_once 'MotionApi.php';

use Illuminate\Foundation\Testing\DatabaseTransactions;

class UpdateMotionApiTest extends MotionApi
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->modelToUpdate = factory($this->class, 'published')->create();

        $this->signInAsRole('administrator');
    }

    /** @test  ******************/
    public function update_motion_with_title()
    {
        $this->updateFieldsGetSee(['title'], 200);
    }

    /** @test  ******************/
    public function update_motion_with_department()
    {
        $this->updateFieldsGetSee(['department_id'], 200);
    }

    /** @test  ******************/
    public function update_motion_with_summary()
    {
        $this->updateFieldsGetSee(['summary'], 200);
    }

    /** @test  ******************/
    public function update_motion_with_text()
    {
        $this->updateFieldsGetSee(['text'], 200,'text',['text']);
    }

    /** @test  ******************/
    public function update_motion_with_department_id()
    {
        $this->updateFieldsGetSee(['department_id'], 200);
    }

    /** @test  ******************/
    public function update_motion_with_closing()
    {
        $this->updateContentGetSee(['closing_at' => \Carbon\Carbon::tomorrow()], 200);
    }

    /** @test  ******************/
    public function update_motion_with_user_id()
    {
        $this->updateFieldsGetSee(['user_id'], 200);
    }

    /** @test  ******************/
    public function update_motion_with_status()
    {
        $this->updateFieldsGetSee(['status'], 200);
    }

    /** @test  ******************/
    public function update_motion_with_budget()
    {
        $this->updateFieldsGetSee(['budget'], 200);
    }

    /** @test  ******************/
    public function update_motion_with_implementation()
    {
        $this->updateFieldsGetSee(['implementation'], 200);
    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES

    /** @test  ******************/
    public function update_motion_with_empty_title_fails()
    {
        $this->updateContentGetSee([
            'title'     => '',
        ], 400);
    }

    /** @test  ******************/
    public function update_motion_content_directly_fails()
    {
        $this->updateContentGetSee([
            'content'     => "Jeremy Flatt, he's Jeremy Flatt, his mum's got 5 cats and a bandana's his hat",
        ], 400);

        $this->updateContentGetSee([
            'content'     => ["text"=>"Jeremy Flatt, he's Jeremy Flatt, his mum's got 5 cats and a bandana's his hat"],
        ], 400);
    }


    /** @test  ******************/
    public function update_motion_with_invalid_department_fails()
    {
        $this->updateContentGetSee([
            'department_id'     => 'An apartment ID',
        ], 400);

        $this->updateContentGetSee([
            'department_id'     => 9000,
        ], 400);
    }

    /** @test  ******************/
    public function update_motion_with_closing_in_past_fails()
    {
        $this->updateContentGetSee([
            'closing_at'     => \Carbon\Carbon::yesterday(),
        ], 400);

        $this->updateContentGetSee([
            'closing_at'     => \Carbon\Carbon::yesterday()->toDateString(),
        ], 400);
    }

    /** @test  ******************/
    public function update_motion_with_invalid_user_fails()
    {
        $this->updateContentGetSee([
            'user_id'     => 99999999,
        ], 400);

        $this->updateContentGetSee([
            'user_id'     => 'Jessica Tran 9000!',
        ], 400);
    }

    /** @test  ******************/
    public function update_motion_with_invalid_status_fails()
    {
        $this->updateContentGetSee([
            'status'     => 99999999,
        ], 400);

        $this->updateContentGetSee([
            'status'     => 'Open',
        ], 400);
    }

    /** @test  ******************/
    public function update_motion_with_empty_implementation_fails()
    {
        $this->updateContentGetSee([
            'implementation'     => '',
        ], 400);
    }
}
