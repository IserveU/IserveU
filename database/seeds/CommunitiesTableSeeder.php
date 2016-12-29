<?php

use App\Community;
use Illuminate\Database\Seeder;

class CommunitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $communities = [
            'name'      => 'Yellowknife',
            'adjective' => 'Yellowknifer',
        ];

        foreach ($communities as $name) {
            Community::create(
                $communities

            );
        }
    }
}
