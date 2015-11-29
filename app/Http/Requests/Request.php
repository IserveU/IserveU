<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

abstract class Request extends FormRequest {

	public function response(Array $errors){
		abort(403,print_r($errors));	
    }
}
