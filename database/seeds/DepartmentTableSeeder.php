<?php

use App\Department;
use Illuminate\Database\Seeder;

class DepartmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departments = [
            'Unknown',
            'City Administrator',
            'Community Services',
            'Corporate Services',
            'Communications and Economic Development',
            'Planning and Development',
            'Public Safety',
            'Public Works and Engineering',
        ];

        foreach ($departments as $name) {
            Department::create([
                'active'    => true,
                'name'      => $name,
                'icon'      => '/icons/'.str_slug($name).'.svg',
            ]);
        }
    }
}
