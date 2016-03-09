<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Http\Requests;

use Illuminate\Http\Request;

use App\File;
use App\MotionFile;
use App\Motion;
use App\FileCategory;
use Auth;

class MotionFileController extends ApiController {

	function __construct()
	{
		$this->middleware('jwt.auth',['except'=>['index','show']]);

	}

    public function index(Motion $motion){
    	return $motion->motionFiles;
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(Motion $motion){
		if(!Auth::user()->can('create-motions')){
			abort(401,'You do not have permission to create a motion');
		}

		$motionFileFields 	= (new MotionFile)->fields;
		$fileFields 		= (new File)->fields;
		
		return array_merge($fileFields,$motionFileFields);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Motion $motion, Request $request)
	{
		if(!Auth::user()->can('create-motions')){
			abort(401,'You do not have permission to create a motion');
		}
		
        $file = new File;
      	$file->uploadFile('motion_files','file',$request);		

		if(!$file->save()){
		 	abort(403,$file->errors);
      	}

		$motionFile = new MotionFile;
		$motionFile->motion_id 		= 	$motion->id;
		$motionFile->file_id 		=	$file->id;
		if(!$motionFile->save()){
		 	abort(403,$motionFile->errors);
		}
     	return $motionFile;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Motion $motion, MotionFile $motionFile) //MotionFile 
	{	
		return $motionFile;
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Motion $motion, MotionFile $motionFile)
	{
		if(!Auth::user()->can('create-motions')){
			abort(403,'You do not have permission to create/update motions');
		}

		if(!$motion->user_id!=Auth::user()->id && !Auth::user()->can('administrate-motions')){ //Is not the user who made it, or the site admin
			abort(401,"This user can not edit motion ($id)");
		}

	
		return array_merge($motionFile->file->fields,$motionFile->fields);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Motion $motion, MotionFile $motionFile, Request $request)
	{
		if(!Auth::user()->can('create-motions')){
			abort(403,'You do not have permission to update a motion');
		}

		if(!$motion->user_id!=Auth::user()->id && !Auth::user()->can('administrate-motions')){ //Is not the user who made it, or the site admin
			abort(401,"This user can not edit motion ($motion_id)");
		}

      	// $motionFile->file->uploadFile('motion_files', 'file', $request);		
		
		if($request->input('title')){
			$motionFile->file->title = $request->input('title');
		}

		if(!$motionFile->file->save()){
		 	abort(403,$motionFile->file->errors);
      	}
     	
     	return $motionFile;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Motion $motion, MotionFile $motionFile)
	{
		if(Auth::user()->id != $motion->user_id && !Auth::user()->can('delete-motions')){
			abort(401,"You do not have permission to delete this motion");
		}

		$motionFile->delete();
		return $motionFile;
	}

	/**
	 * Attaches motion file and file id to one another.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function flowUpload(Request $request)
	{
		if(!Auth::user()->can('create-motions')){
			abort(403,'You do not have permission to update a motion');
		}

		$motionFile = new MotionFile;
		$motionFile->motion_id = $request->input('motion_id');
		$motionFile->file_id   = $request->input('file_id');
		if(!$motionFile->save()){
		 	abort(403,$motionFile->errors);
		}
     	return $motionFile;
	}


}
