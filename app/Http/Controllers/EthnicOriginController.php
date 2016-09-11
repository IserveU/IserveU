<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\EthnicOrigin;


use Illuminate\Http\Request;

class EthnicOriginController extends ApiController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return EthnicOrigin::all();
	}


}
