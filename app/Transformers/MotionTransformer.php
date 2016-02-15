<?php 

namespace App\Transformers;


class MotionTransformer extends Transformer {


	public function transform($motion)
	{

		$transformedMotion = [

			'slug'		 => str_slug($motion['title']),
	 		'closing'	 => is_array($motion['closing']) ?: formatIntoReadableDate( $motion['closing'] ),
	 		'updated_at' => is_array($motion['updated_at']) ?: formatIntoReadableDate( $motion['updated_at'] )

		];

		return array_merge($motion, $transformedMotion);
	}


}