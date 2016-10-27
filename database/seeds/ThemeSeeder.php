<?php

use App\Page;
use App\EthnicOrigin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \File::copy(base_path().'/tests/unit/File/test.png', storage_path('app/'.$fileName));

        $logo = File::create();

    }
}
