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
        $array = array(1, -1);
        $today = new DateTime('now');

    	foreach(range(1, 1000) as $index){
        DB::table('comments')->insert([
            'text' => $faker->sentence($nbWords = 15),
            'vote_id' => $index,
            'created_at' => $today
            ]);

        // test comment votes
        foreach(range(1, 1000) as $secondindex){
            DB::table('comment_votes')->insert([
                'position' => array_rand($array),
                'comment_id' => $index,
                'vote_id' => $secondindex
                ]);
            }
        }
    }
}
