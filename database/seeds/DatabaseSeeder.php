<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(EthnicOriginTableSeeder::class);
        $this->command->info('Ethnic origins seeded');

        $this->call(DepartmentTableSeeder::class);
        $this->command->info('Departments seeded');

        $this->call(CommunitiesTableSeeder::class);
        $this->command->info('Communitites seeded');

        $this->call(HomePageSeeder::class);
        $this->command->info('Home Page Seeded');

        $this->call(ThemeSeeder::class);
        $this->command->info('Theme files seeded');
    }
}
