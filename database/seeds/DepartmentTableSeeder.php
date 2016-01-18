<?php

use Illuminate\Database\Seeder;

use App\Department;

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

		foreach($departments as $name){
			$department = new Department;
			$department->active = true;
			$department->name 	= $name;
			$department->save();
		}

    }
}




