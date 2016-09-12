<?php

namespace App\Http\Controllers;


use App\Http\Requests;
use Illuminate\Http\Request;

use Auth;
use Validator;
use Input;
use App\BackgroundImage;
use App\File;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManagerStatic as Image;

use Storage;

class BackgroundImageController extends ApiController
{


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $backgroundImage = BackgroundImage::all();
        return $backgroundImage;
    }



    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(array('image' => Input::file('image')),array('image'=>'image'));
        if ($validator->fails()){
            abort(403,'file is not an image');
        }
        
        $file = new File;
        $file->uploadFile('background_images', 'background_images', $request);     

        if(!$file->save()){
            abort(403,$file->errors);
        }

        $backgroundImage = (new BackgroundImage)->secureFill($request->all());

        $backgroundImage->user_id = Auth::user()->id;
        $backgroundImage->file_id = $file->id;
      
        if(!$backgroundImage->save()){
            abort(403,$backgroundImage->errors);
        }

        return $backgroundImage;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(BackgroundImage $backgroundImage)
    {
        $backgroundImage  = BackgroundImage::find($backgroundImage->id); 

        return $backgroundImage;
    }

  
    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(BackgroundImage $backgroundImage, Request $request)
    {
        $validator = Validator::make(array('image' => Input::file('image')),array('image'=>'image'));
        if ($validator->fails()){
            abort(403,'file is not an image');
        }

        $file = $backgroundImage->file;
        $file->uploadFile('background_images', 'background_images', $request);

        if(!$file->save()){
            abort(403,$file->errors);
        }

        $backgroundImage->secureFill($request->all());
              
        if(!$backgroundImage->save()){
            abort(403,$backgroundImage->errors);
        }

        return $backgroundImage; 

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(BackgroundImage $backgroundImage)
    {

        if(!Auth::user()->can('administrate-background_image')){
            abort(401,'User does not have permission to delete background images');
        }

        $backgroundImage->delete();
    }

}
