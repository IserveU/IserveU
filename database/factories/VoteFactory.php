<?php


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

$factory->defineAs(App\Vote::class, 'agree', function ($faker) use ($factory) {
    $vote = $factory->raw(App\Vote::class);

    return array_merge($vote, ['position'=>1]);
});

$factory->defineAs(App\Vote::class, 'on_closed', function ($faker) use ($factory) {
    $vote = $factory->raw(App\Vote::class);
    $closedMotion = factory(App\Motion::class, 'closed')->create();

    return array_merge($vote, ['motion_id'=> $closedMotion->id]);
});
