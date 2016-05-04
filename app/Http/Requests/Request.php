<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Auth;


abstract class Request extends FormRequest {

	protected function formatErrors(Validator $validator)
	{
		return $validator->errors()->all();
	    //return $validator->errors()->all();
	}

	public function response(array $errors)
	{
		//Might need to just apply errors to the specific methods
	    return new JsonResponse($errors, 400);
	}

    // OPTIONAL OVERRIDE
    public function forbiddenResponse()
    {
	    if(!Auth::check()){
	        return \Redirect::guest('/login');
	    }

        //Probably someone trying to get into something they haven't got permission
        return new Response("Forbidden", 403); 
    }
}
