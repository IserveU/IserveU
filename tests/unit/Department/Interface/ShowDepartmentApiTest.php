<?php

include_once 'DepartmentApi.php';

use Illuminate\Foundation\Testing\DatabaseTransactions;

class ShowDepartmentApiTest extends DepartmentApi
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
    }

    /////////////////////////////////////////////////////////// CORRECT RESPONSES

    /** @test */
    public function show_department_test()
    {
        $this->signInAsRole('administrator');

        $department = factory(App\Department::class)->create();


        $this->visit('/api/department/'.$department->slug)
            ->assertResponseStatus(200)
            ->seeJsonStructure([
                'name', 'slug', 'active',
            ])
            ->dontSeeJson([
                'motions',
            ]);
    }
}
