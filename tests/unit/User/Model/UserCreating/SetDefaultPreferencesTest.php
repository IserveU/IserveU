<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Arr;

class SetDefaultPreferancesTest extends BrowserKitTestCase
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
                            'on'  => 1,
                        ],
                        'summary'  => [
                            'on'  => 1,
                        ],
                    ],
                    'user' => [
                        'onrolechange' => [
                            'on'  => 0,
                        ],
                    ],
                ],
            ],
            'motion' => [
                'notify' => [
                    'admin' => [
                        'summary' => [
                            'on'  => 0,
                        ],
                    ],
                    'user' => [
                        'onchange' => [
                            'on'  => 0,
                        ],
                        'summary'  => [
                            'on'        => 0,
                            'times'     => [
                              'friday'    => null,
                              'monday'    => null,
                              'saturday'  => null,
                              'sunday'    => 17,
                              'thursday'  => null,
                              'tuesday'   => null,
                              'wednesday' => null,
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $this->assertEquals(json_encode(Arr::sortRecursive($expected)), json_encode(Arr::sortRecursive($user->preferences)));
    }
}
