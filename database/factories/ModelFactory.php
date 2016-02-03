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


//https://github.com/fzaninotto/Faker

$factory->define(App\User::class, function ($faker) use ($factory) {

    return [
        'first_name'        => $faker->firstName,
        'middle_name'       => $faker->name,
        'last_name'         => $faker->lastName,
        'email'             => $faker->email,
        'password'          => 'abcd1234',
        'remember_token'    => str_random(10),  
        'ethnic_origin_id'	=> $faker->numberBetween($min = 1, $max = 23),
        'date_of_birth'		=> $faker->dateTimeBetween($startDate = '-30 years', $endDate = 'now'),
        'login_attempts'	=> 0,
        'identity_verified' => 0,
        'created_at'        => \Carbon\Carbon::now()
    ];

});


$factory->defineAs(App\User::class, 'unverified', function (Faker\Generator $faker)  use ($factory) {

    $user = $factory->raw(App\User::class);

    $user['identity_verified'] = 0;

    return $user;
});


$factory->defineAs(App\User::class, 'verified', function (Faker\Generator $faker)  use ($factory) {

    $user = $factory->raw(App\User::class);

    $user['identity_verified'] = 1;

    return $user;
});

$factory->defineAs(App\User::class, 'public', function (Faker\Generator $faker)  use ($factory) {

    $user = $factory->raw(App\User::class);

    return array_merge($user, ['public' => 1]);
});

$factory->defineAs(App\User::class, 'private', function (Faker\Generator $faker)  use ($factory) {

    $user = $factory->raw(App\User::class);

    return array_merge($user, ['public' => 0]);
});



/************************* Different Motion Status Factories ***********************************/

$factory->define(App\Motion::class, function ($faker) use ($factory) {

    $admin = DB::table('role_user')->where('role_id', '=', 1)->first();

    if(!$admin) {
        $admin = $factory->raw(App\User::class)->make();
        $admin->addUserRoleByName('administrator');
        $admin->user_id = $admin->id;
    }

    return [
        'title'         => $faker->sentence($nbWords = 6),
        'summary'       => $faker->sentence($nbWords = 15),
        'department_id' => $faker->biasedNumberBetween($min = 1, $max = 8, $function = 'sqrt'),
        'closing'       => $faker->date(),
        'user_id'       => $admin->user_id,
        'text'          => $faker->paragraph($nbSentences =10),
        'created_at'    => \Carbon\Carbon::now()
    ];
});


$factory->defineAs(App\Motion::class, 'draft', function (Faker\Generator $faker)  use ($factory) {

    $motion = $factory->raw(App\Motion::class);

    return array_merge($motion, ['status' => 0]);
});

$factory->defineAs(App\Motion::class, 'review', function (Faker\Generator $faker)  use ($factory) {

    $motion = $factory->raw(App\Motion::class);

    return array_merge($motion, ['status' => 1]);
});

$factory->defineAs(App\Motion::class, 'published', function (Faker\Generator $faker)  use ($factory) {

    $motion = $factory->raw(App\Motion::class);

    return array_merge($motion, ['status' => 2]);
});

$factory->defineAs(App\Motion::class, 'closed', function (Faker\Generator $faker)  use ($factory) {

    $motion = $factory->raw(App\Motion::class);

    $date = \Carbon\Carbon::now();

    return array_merge($motion, ['status' => 3]);
});


/************************* Different Comment Factories ***********************************/


$factory->define(App\Comment::class, function ($faker) use ($factory) {

    return [
        'title'         => $faker->sentence($nbWords = 6),
        'summary'       => $faker->sentence($nbWords = 15),
    ];

});

