<?php

include_once 'DepartmentApi.php';

use Illuminate\Foundation\Testing\DatabaseTransactions;

class UpdateDepartmentApiTest extends DepartmentApi
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->modelToUpdate = factory($this->class)->create();

        $this->signInAsRole('administrator');
    }

    /** @test  ******************/
    public function update_department_with_title()
    {
        $this->updateFieldsGetSee(['name'], 200);
    }

    /** @test  ******************/
    public function update_department_with_department()
    {
        $this->updateFieldsGetSee(['active'], 200);
    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES

    /** @test  ******************/
    public function update_department_with_empty_name_fails()
    {
        $this->updateContentGetSee([
            'name' => '',
        ], 400);
    }

    /** @test  ******************/
    public function update_department_with_nonboolean_active_fails()
    {
        $this->updateContentGetSee([
            'active' => 'Hyperdrive Maximum',
        ], 400);

        $this->updateContentGetSee([
            'active' => 'true',
        ], 400);

        $this->updateContentGetSee([
            'active' => 9001,
        ], 400);
    }

    /** @test  ******************/
    public function update_department_slug_fails()
    {
        $this->updateContentGetSee([
            'slug' => 'overriderstrider',
        ], 400);
    }
}
