<?php


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
        'email'                => $faker->email.rand(1940, 2020),
        'password'             => $faker->password,
        'postal_code'          => 'X1A1A4',
        'ethnic_origin_id'     => $ethnicOrigin ? $ethnicOrigin->id : null,
        'phone'                => rand(18670000000, 18680000000),
        'date_of_birth'        => $faker->dateTimeBetween($startDate = '-30 years', $endDate = 'now')->format('Y-m-d'),
        'login_attempts'       => 0,
        'identity_verified'    => 0,
        'created_at'           => \Carbon\Carbon::now(),
        'community_id'         => $community ? $community->id : null,
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

    return array_merge($user, ['identity_verified' => 1, 'address_verified_until' => \Carbon\Carbon::now()->addYears(1)]);
});

$factory->defineAs(App\User::class, 'public', function (Faker\Generator $faker) use ($factory) {
    $user = $factory->raw(App\User::class);

    return array_merge($user, ['status' => 'public']);
});

$factory->defineAs(App\User::class, 'private', function (Faker\Generator $faker) use ($factory) {
    $user = $factory->raw(App\User::class);

    return array_merge($user, ['status' => 'private']);
});
