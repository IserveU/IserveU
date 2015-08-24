<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;


class CommentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$faker = Faker::create();


    	foreach(range(1, 10000) as $index){
        DB::table('comments')->insert([
            'text' => $faker->sentence($nbWords = 15),
            'vote_id' => $index
            ]);

        // test comment votes

        DB::table('comment_votes')->insert([
            'position' => $faker->biasedNumberBetween($min = -1, $max = 1, $function = 'sqrt'),
            'comment_id' => $index,
            'vote_id' => $index
            ]);
        }
    }
}
