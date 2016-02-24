<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;


class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$faker = Faker::create();

    	foreach(range(1,20) as $index){

	        DB::table('users')->insert([
	           	'first_name' => $faker->firstName,
	            'last_name'  => $faker->lastName,
	            'password'   => bcrypt('abcd1234'),
	            'email'		 => $faker->safeEmail
	    	]);

        }
    }
}
