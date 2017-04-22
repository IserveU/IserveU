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
        'text'          => $faker->sentences(4, true),
    ];
});

$factory->define(App\Department::class, function ($faker) use ($factory) {
    return [
        'name'          => $faker->word.rand(0, 100).' '.$faker->word.rand(0, 9),
        'active'        => 1,
    ];
});

/************************* Community Factories ***********************************/

$factory->define(App\Community::class, function ($faker) use ($factory) {
    $town = $faker->city;

    return [
        'name'          => $town,
        'active'        => 1,
        'adjective'     => "Person from $town",
    ];
});
