<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class MotionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$faker = Faker::create();

        $today = new DateTime('now');

    	foreach(range(1,10) as $index){

        DB::table('motions')->insert([
           	'title' => $faker->sentence($nbWords = 6),
            'summary' => $faker->sentence($nbWords = 15),
            'status' => 2,
            'department_id' => $faker->biasedNumberBetween($min = 1, $max = 8, $function = 'sqrt'),
            'closing' => $faker->date(),
            'user_id' => 1,
            'text' => $faker->paragraph($nbSentences =10),
            'created_at' => $today
        	]);

        }


    }
}
