<?php
/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/


$factory->define(App\User::class, function ($faker) use ($factory) {
    $ethnicOrigin = \App\EthnicOrigin::orderBy(\DB::raw('RAND()'))->first();
    $community = \App\Community::orderBy(\DB::raw('RAND()'))->first();

    //If not defined, then random status
    $statuses = ['public', 'private'];
    $status = $statuses[array_rand($statuses)];

    return [
        'first_name'           => $faker->firstName,
        'middle_name'          => $faker->name,
        'last_name'            => $faker->lastName,
        'email'                => $faker->email,
        'password'             => $faker->password,
        'postal_code'          => 'X1A1A4',
        'phone'                => rand(18670000000, 18680000000),
        'ethnic_origin_id'     => $ethnicOrigin->id,
        'date_of_birth'        => $faker->dateTimeBetween($startDate = '-30 years', $endDate = 'now')->format('Y-m-d'),
        'login_attempts'       => 0,
        'identity_verified'    => 0,
        'created_at'           => \Carbon\Carbon::now(),
        'community_id'         => $community->id,
        'street_name'          => $faker->streetName,
        'unit_number'          => $faker->randomDigit.$faker->randomLetter,
        'status'               => $status,
        'agreement_accepted'   => 1,
    ];
});


$factory->defineAs(App\User::class, 'unverified', function (Faker\Generator $faker) use ($factory) {
    $user = $factory->raw(App\User::class);

    return array_merge($user, ['identity_verified' => 0]);
});


$factory->defineAs(App\User::class, 'verified', function (Faker\Generator $faker) use ($factory) {
    $user = $factory->raw(App\User::class);
    \Log::info('going to add verified to user');

    return array_merge($user, ['identity_verified' => 1]);
});

$factory->defineAs(App\User::class, 'public', function (Faker\Generator $faker) use ($factory) {
    $user = $factory->raw(App\User::class);

    return array_merge($user, ['status' => 'public']);
});

$factory->defineAs(App\User::class, 'private', function (Faker\Generator $faker) use ($factory) {
    $user = $factory->raw(App\User::class);

    return array_merge($user, ['status' => 'private']);
});





/************************* Different Motion Status Factories ***********************************/

$factory->define(App\Motion::class, function ($faker) use ($factory) {

    //If not defined, then random status
    $statuses = ['draft', 'review', 'published'];
    $status = $statuses[array_rand($statuses)];

    $implementations = ['binding', 'non-binding'];
    $implementation = $implementations[array_rand($implementations)];

    $department = factory(App\Department::class)->create();

    return [
        'title'         => $faker->sentence($nbWords = 6),
        'summary'       => $faker->sentence($nbWords = 15),
        'department_id' => $department->id,
        'user_id'       => function () {
            return factory(App\User::class, 'verified')->create()->id;
        },
        'closing_at'       => Carbon\Carbon::now()->addDays(rand(1, 5)),
        'text'             => $faker->paragraph($nbSentences = 10),
        'created_at'       => Carbon\Carbon::now()->subDays(rand(5, 30)),
        'implementation'   => $implementation,
        'status'           => $status,
    ];
});


$factory->defineAs(App\Motion::class, 'draft', function (Faker\Generator $faker) use ($factory) {
    $motion = $factory->raw(App\Motion::class);

    return array_merge($motion, ['status' => 'draft',
                                'title'   => $faker->sentence($nbWords = 4).' Draft', ]
                    );
});

$factory->defineAs(App\Motion::class, 'review', function (Faker\Generator $faker) use ($factory) {
    $motion = $factory->raw(App\Motion::class);

    return array_merge($motion, ['status' => 'review',
                                'title'   => $faker->sentence($nbWords = 4).' Review', ]
                    );
});

$factory->defineAs(App\Motion::class, 'published', function (Faker\Generator $faker) use ($factory) {
    $motion = $factory->raw(App\Motion::class);


    return array_merge($motion, array_merge(createClosingDate(), ['status' => 'published',
                'title'                                                    => $faker->sentence($nbWords = 4).' Published', ]));
});

$factory->defineAs(App\Motion::class, 'closed', function (Faker\Generator $faker) use ($factory) {
    $motion = $factory->raw(App\Motion::class);

    $date = \Carbon\Carbon::now();

    return array_merge($motion, ['status'      => 'closed',
                                'closing_at'   => Carbon\Carbon::now()->subDays(rand(1, 5)),
                                'title'        => $faker->sentence($nbWords = 4).' Closed', ]
                    );
});


/************************* Comment Factories ***********************************/


$factory->define(App\Comment::class, function ($faker) use ($factory) {
    return [
        'text'      => $faker->sentence($nbWords = 10),
        'status'    => 'public',
        'vote_id'   => function () {
            return factory(App\Vote::class)->create()->id;
        },
    ];
});



/************************* Vote Factories ***********************************/


$factory->define(App\Vote::class, function ($faker) use ($factory) {
    $citizenUser = factory(App\User::class, 'verified')->create();
    $citizenUser->addRole('citizen');

    return [
        'position'      => $faker->numberBetween($min = -1, $max = 1),
        'motion_id'     => function () {
            return factory(App\Motion::class, 'published')->create()->id;
        },
        'user_id'        => $citizenUser->id,
    ];
});


/************************* Comment Vote Factories ***********************************/

$factory->define(App\CommentVote::class, function ($faker) use ($factory) {
    $comment = factory(App\Comment::class)->create();

    //A second vote on that comment
    $vote = factory(App\Vote::class)->create([
        'motion_id' => $comment->vote->motion_id,
    ]);

    return [
        'position'      => $faker->numberBetween($min = -1, $max = 1),
        'comment_id'    => $comment->id,
        'vote_id'       => $vote->id,
    ];
});


/************************* Page Factories ***********************************/

$factory->define(App\Page::class, function ($faker) use ($factory) {
    return [
        'title'         => $faker->sentence($nbWords = 6),
        'content'       => $faker->sentences(4, true),
    ];
});




$factory->define(App\Department::class, function ($faker) use ($factory) {
    return [
        'name'          => $faker->word.rand(0, 9).' '.$faker->word.rand(0, 9),
        'active'        => 1,
    ];
});
