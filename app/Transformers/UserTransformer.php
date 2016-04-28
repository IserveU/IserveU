<?php

namespace App\Transformers;

use App\Community;

class UserTransformer extends Transformer
{

	public function transform($user)
	{

        $transformedUser = [
            'community' => $user->community?$user->community->name:null
        ];

        $user = $user->toArray();

        return array_merge($user, $transformedUser);
	}

}