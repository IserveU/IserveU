<?php

namespace App\Transformers;

use App\Community;

use App\Policies\UserPolicy;

class UserTransformer extends Transformer
{


	public function transform($user)
	{

		$user = (new UserPolicy())->getVisible($user);

        $transformedUser = [
            'community' => $user->community?$user->community->name:null
        ];

        return array_merge($user->toArray(), $transformedUser);
	}

}