<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Arr;

class SetDefaultPreferancesTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
    }

    /** @test **/
    public function default_preferences_set()
    {
        $user = factory(App\User::class)->create();

        $expected = [
            'authentication' => [
                'notify' => [
                    'admin' => [
                        'oncreate' => 1,
                        'summary'  => 1,
                    ],
                    'user' => [
                        'onrolechange' => 1,
                    ],
                ],
            ],
            'motion' => [
                'notify' => [
                    'admin' => [
                        'summary' => 0,
                    ],
                    'user' => [
                        'onchange' => 0,
                        'summary'  => 0,
                    ],
                ],
            ],
        ];

        $this->assertEquals(json_encode(Arr::sortRecursive($expected)), json_encode(Arr::sortRecursive($user->preferences)));
    }
}
