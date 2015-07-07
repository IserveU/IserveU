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

$factory->define(App\EthnicOrigins::class, function ($faker) {
    return [EthnicOrigins::lists('id')];
});


$factory->define(App\User::class, function ($faker) use ($factory) {

   // $ethnicorigin = $factory->raw(App\EthnicOrigins::class);
    return [
        'first_name' => $faker->firstName,
        'middle_name' => $faker->name,
        'last_name' => $faker->lastName,
        'email' => $faker->email,
        'password' => str_random(10),
        'remember_token' => str_random(10),  
        'ethnic_origin_id'	=>  1,
        'date_of_birth'		=>	$faker->dateTimeBetween($startDate = '-30 years', $endDate = 'now'),
        'public'			=>	$faker->boolean(50),
        'login_attempts'	=>	0,
        'created_at' => 2015-06-26
        ];
});


