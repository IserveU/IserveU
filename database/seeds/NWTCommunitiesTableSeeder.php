<?php

use Illuminate\Database\Seeder;

use App\Community;


class NWTCommunitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $communities = [
			'Aklavik',
			'Behchokǫ̀',
			'Colville Lake',
			'Deline',
			'Enterprise',
			'Fort Good Hope',
			'Fort Liard',
			'Fort McPherson',
			'Fort Providence',
			'Fort Resolution',
			'Fort Simpson',
			'Fort Smith',
			'Gametì',
			'Hay River',
			'Inuvik',
			'Jean Marie River',
			'Kakisa',
			'Kátł’odeeche First Nation',
			'Nahanni Butte',
			'Norman Wells',
			'Paulatuk',
			'Sachs Harbour',
			'Trout Lake',
			'Tsiigehtchic',
			'Tuktoyaktuk',
			'Tulita',
			'Ulukhaktok',
			'Wekweètì',
			'Whatì',
			'Wrigley',
			'Yellowknife – City of Yellowknife',
			'Yellowknife – Yellowknives Dene First Nation (Dettah)',
			'Łutselk’e'
        ];

		foreach($communities as $name){
			$communities = new Community;
			$communities->active = true;
			$communities->name 	= $name;
			$communities->save();
		}
    }
}
