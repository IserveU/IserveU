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
                        'oncreate' => [
                            'on'  =>  1,
                        ],
                        'summary'  => [
                            'on'  =>  1,
                        ]
                    ],
                    'user' => [
                        'onrolechange' => [
                            'on'  =>  1,
                        ]
                    ],
                ],
            ],
            'motion' => [
                'notify' => [
                    'admin' => [
                        'summary' => [
                            'on'  =>  0,
                        ]
                    ],
                    'user' => [
                        'onchange' => [
                            'on'  =>  0,
                        ],
                        'summary'  => [
                            'on'        =>  0,
                            'frequency' =>  "0 18 * * 0"
                        ]
                    ],
                ],
            ],
        ];

        $this->assertEquals(json_encode(Arr::sortRecursive($expected)), json_encode(Arr::sortRecursive($user->preferences)));
    }
}
