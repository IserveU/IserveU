<?php

include_once 'DepartmentApi.php';

class IndexDepartmentApiTest extends DepartmentApi
{
    protected static $departments;

    public function setUp()
    {
        parent::setUp();

        if (is_null(static::$departments)) {
            static::$departments = factory(App\Department::class, 25)->create();
        }
        factory(App\Department::class, 5)->create();
    }

    ///////////////////////////////////////////////////////////CORRECT RESPONSES

    /** @test */
    public function department_filter_defaults()
    {
        $this->get($this->route)
            ->assertResponseStatus(200)
            ->seeJsonStructure([
                'total',
                'per_page',
                'current_page',
                'last_page',
                'next_page_url',
                'prev_page_url',
                'from',
                'to',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'slug',
                        'active',
                    ],
                ],
            ]);
    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES
}
