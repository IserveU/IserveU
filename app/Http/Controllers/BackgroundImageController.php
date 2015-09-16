<?php

namespace App\Http\Controllers;


use Request;
use Auth;
use Validator;
use Input;
use App\BackgroundImage;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManagerStatic as Image;

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
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $validator = Validator::make(array('image' => Input::file('image')),array('image'=>'image'));
        if ($validator->fails()){
            abort(403,'file is not an image');
        }
        
        $img = Image::make(Request::file('file'))->resize(1920,1080);
       
        $file = md5($img->response()).".jpg";
        //$directory .="/database/seeds/thefile.csv";
        $img->save(getcwd()."/uploads/background_images/$file");

        $input = Request::all();
        $input['file'] = $file;

        $backgroundImage = (new BackgroundImage)->secureFill($input);

        $backgroundImage->user_id = Auth::user()->id;

      
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

}
