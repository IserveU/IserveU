<?php

use App\EthnicOrigin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class EthnicOriginTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv = Storage::disk('csv')->get('ethnic_origins.csv');

        $array = array_slice(explode("\n", $csv), 1);

        foreach ($array as $data) {
            $row = explode(',', $data, 2);

            $ethnic_origin = new EthnicOrigin();
            $ethnic_origin->description = str_replace('"', '', $row[1]);
            $ethnic_origin->region = $row[0];
            $ethnic_origin->save();
        }
    }
}
