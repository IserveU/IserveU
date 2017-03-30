<?php

include_once 'DepartmentApi.php';

use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeleteDepartmentApiTest extends DepartmentApi
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
    }

    /////////////////////////////////////////////////////////// CORRECT RESPONSES

    /** @test  ******************/
    public function delete_department_correct_response()
    {
        $this->signInAsRole('administrator');

        $department = factory(App\Department::class)->create();

        $this->delete('/api/department/'.$department->slug)
            ->assertResponseStatus(200);
    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES
}
