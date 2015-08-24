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

// $factory->defineAs(App\Role::class, 'administrator', function($faker) {

//     return [
//         'name' => 'administrator',
//     ];
// });


// $factory->defineAs(App\User::class, 'administrator', function ($faker) use ($factory){

//     $user = $factory->raw(App\User::class);

//     $admin = DB::table('role_user')
//             ->join('users', 'role_user.user_id', '=', 'users.id')
//             ->join('', 'role_user.role_id', '=', 1)
//             ->get();

//     return ($user, $admin);

//     // return [
//     //     factory('App\User')->create()->roles()->save(App\Role::find(1)),
//     // ];

// });


$factory->define(App\Motion::class, function ($faker){

    return [
        'title' => $faker->sentence($nbWords = 6),
        'summary' => $faker->sentence($nbWords = 15),
        'active' => 1,
        'department_id' => $faker->biasedNumberBetween($min = 1, $max = 8, $function = 'sqrt'),
        'closing' => 2015-08-7,
        'user_id' => 1,
        'text' => $faker->paragraph($nbSentences =10),
        'created_at' => 2015-08-01
    ];

});
