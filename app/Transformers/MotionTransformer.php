<?php 

namespace App\Transformers;

use App\Department;
use App\Motion;
use App\Motion\MotionSection;
use App\File;

class MotionTransformer extends Transformer {


	public function transform($motion)
	{
		if(!is_array($motion)) {
			$motionArray = $motion->toArray();
		}

		$department = Department::find($motionArray['department_id']);

		$transformedMotion = [
			'slug'		 => $motionArray['slug'],
	 		'closing'	 => array_key_exists('closing', $motionArray) ? formatIntoReadableDate( $motionArray['closing'] ) : null,
	 		'updated_at' => is_array($motionArray['updated_at']) ?: formatIntoReadableDate( $motionArray['updated_at'] ),
	 		'icon'		 => str_slug($department['name'], $separator = '_'),
	 		'department' => $department['name'],
			'sections'   => $motion->typedSections

		];

		return array_merge($motionArray, $transformedMotion);
	}

}