<?php


/************************* Different Motion Status Factories ***********************************/

$factory->define(App\Motion::class, function ($faker) use ($factory) {

    //If not defined, then random status
    $statuses = ['draft', 'review', 'published'];
    $status = $statuses[array_rand($statuses)];

    $implementations = ['binding', 'non-binding'];
    $implementation = $implementations[array_rand($implementations)];

    $department = \App\Department::inRandomOrder()->take(1)->first();

    return [
        'title'         => $faker->sentence($nbWords = 6),
        'summary'       => $faker->sentence($nbWords = 15),
        'department_id' => $department->id,
      // The only thing that sets the published at field is the status field
        'user_id' => function () {
            return factory(App\User::class, 'verified')->create()->id;
        },
        'closing_at'     => Carbon\Carbon::now()->addDays(rand(1, 5)),
        'text'           => $faker->paragraph($nbSentences = 10),
        'created_at'     => Carbon\Carbon::now()->subDays(rand(5, 30)),
        'implementation' => $implementation,
        'status'         => $status,
    ];
});

$factory->defineAs(App\Motion::class, 'draft', function (Faker\Generator $faker) use ($factory) {
    $motion = $factory->raw(App\Motion::class);

    return array_merge($motion, ['status' => 'draft',
                                'title'   => $faker->sentence($nbWords = 4).' Draft',
                               ]
                    );
});

$factory->defineAs(App\Motion::class, 'review', function (Faker\Generator $faker) use ($factory) {
    $motion = $factory->raw(App\Motion::class);

    return array_merge($motion, ['status' => 'review',
                                'title'   => $faker->sentence($nbWords = 4).' Review',
                            ]
                    );
});

$factory->defineAs(App\Motion::class, 'published', function (Faker\Generator $faker) use ($factory) {
    $motion = $factory->raw(App\Motion::class);

    return array_merge($motion, array_merge(createClosingDate(), [
                  'status' => 'published',
                  'title'  => $faker->sentence($nbWords = 4).' Published',
                ]));
});

$factory->defineAs(App\Motion::class, 'closed', function (Faker\Generator $faker) use ($factory) {
    $motion = $factory->raw(App\Motion::class);

    $date = \Carbon\Carbon::now();

    return array_merge($motion, ['status'    => 'closed',
                                'closing_at' => Carbon\Carbon::now()->subDays(rand(1, 5)),
                                'title'      => $faker->sentence($nbWords = 4).' Closed',
                              ]
                    );
});

// Seeding Factories
