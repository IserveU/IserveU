<?php 

namespace App\Transformers;

use App\Department;

class MotionTransformer extends Transformer {


	public function transform($motion)
	{

		if(!is_array($motion)) {
			$motion = $motion->toArray();
		}

		$department = Department::find($motion['department_id']);

		$transformedMotion = [

			'slug'		 => str_slug($motion['title']),
	 		'closing'	 => is_array($motion['closing']) ?: formatIntoReadableDate( $motion['closing'] ),
	 		'updated_at' => is_array($motion['updated_at']) ?: formatIntoReadableDate( $motion['updated_at'] ),
	 		'icon'		 => str_slug($department['name'], $separator = '_')

		];

		return array_merge($motion, $transformedMotion);
	}


}