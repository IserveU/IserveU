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
            'active' => 1,
            'department_id' => $faker->biasedNumberBetween($min = 1, $max = 8, $function = 'sqrt'),
            'closing' => $faker->date(),
            'user_id' => 1,
            'text' => $faker->paragraph($nbSentences =10),
            'created_at' => $today
        	]);

        foreach(range(1, 100) as $secondindex){
        // test votes

        DB::table('votes')->insert([
            'position' => $faker->biasedNumberBetween($min = -1, $max = 1, $function = 'sqrt'),
            'motion_id' => $index,
            'user_id' => $secondindex,
            'created_at' => $today
            ]);
        // test comments

    }

        }


    }
}
