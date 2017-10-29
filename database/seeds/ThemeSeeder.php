<?php

use App\File;
use App\Page;
use App\Setting;
use App\User;
use Illuminate\Database\Seeder;

class ThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \File::copyDirectory(base_path().'/resources/assets/logo', storage_path('app/logo'));

        $adminUser = User::first();
        $homePage = Page::first();

        $logo = File::create([
            'filename' => 'logo.png',
            'folder'   => 'logo',
            'type'     => 'image',
            'user_id'  => $adminUser->id,
            'title'    => 'Default site logo',
        ]);

        $homePage->files()->save($logo);

        Setting::set('theme.logo', $logo->slug);

        $logoMono = File::create([
            'filename' => 'logo_allwhite.png',
            'folder'   => 'logo',
            'type'     => 'image',
            'user_id'  => $adminUser->id,
            'title'    => 'Default monochrome site logo',
        ]);

        Setting::set('theme.logo_mono', $logoMono->slug);

        $homePage->files()->save($logoMono);

        $symbol = File::create([
            'filename' => 'symbol.png',
            'folder'   => 'logo',
            'type'     => 'image',
            'user_id'  => $adminUser->id,
            'title'    => 'Default site symbol',
        ]);

        Setting::set('theme.symbol', $symbol->slug);

        $homePage->files()->save($symbol);

        $symbolMono = File::create([
            'filename' => 'symbol_allwhite.png',
            'folder'   => 'logo',
            'type'     => 'image',
            'user_id'  => $adminUser->id,
            'title'    => 'Default monochrome site symbol',
        ]);

        Setting::set('theme.symbol_mono', $symbolMono->slug);

        $homePage->files()->save($symbolMono);

        Setting::save();
    }
}
