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
            'Yellowknife',
        ];

        foreach ($communities as $name) {
            $communities = new Community();
            $communities->active = true;
            $communities->name = $name;
            $communities->save();
        }
    }
}
