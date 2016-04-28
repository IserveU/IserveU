<?php

namespace App\Transformers;

use App\Department;

class DepartmentTransformer extends Transformer
{

	public function transform($department)
	{
        $transformedDepartment = [
            'icon' => str_slug($department['name'], $separator = '_')
        ];

        return array_merge($department, $transformedDepartment);
	}

}