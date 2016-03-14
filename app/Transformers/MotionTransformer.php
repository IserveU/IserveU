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
			$motion = $motion->toArray();
		}

		$department = Department::find($motion['department_id']);

		$transformedMotion = [

			'slug'		 => str_slug($motion['title']),
	 		'closing'	 => is_array($motion['closing']) ?: formatIntoReadableDate( $motion['closing'] ),
	 		'updated_at' => is_array($motion['updated_at']) ?: formatIntoReadableDate( $motion['updated_at'] ),
	 		'icon'		 => str_slug($department['name'], $separator = '_'),
	 		'department' => $department['name'],
			'section'    => self::getMotionSection($motion['id'])

		];

		return array_merge($motion, $transformedMotion);
	}


	// for some reason the relationship is not working
	// obviously as php this should get a refactor
	public function getMotionSection($id)
	{
		 $sections = MotionSection::where('motion_id', '=', $id)->get();

		 if($sections->isEmpty()){
		 	return null;
		 }

		 $sections = $sections->toArray();

		 $content = array_get($sections, '0.content');
		 if(isset($content->bio->avatar_id)){
		 	$content->bio->avatar = File::find($content->bio->avatar_id)->filename;
		 }

		 if( isset($content) ){

			 $content->id = array_get($sections, '0.id');
		 }

		 return $content;
	
	}



}