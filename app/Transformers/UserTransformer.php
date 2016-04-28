<?php

namespace App\Transformers;

use App\Community;

class UserTransformer extends Transformer
{

	public function transform($user)
	{

        $transformedUser = [
            'community' => Community::find($user['community_id'])->name
        ];

        return array_merge($user, $transformedUser);
	}

}