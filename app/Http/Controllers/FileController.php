<?php

namespace App\Http\Controllers;

use App\File;
use App\Motion;
use Storage;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests\File\StoreUpdateFileRequest;
use Theme;

use Symfony\Component\HttpFoundation\BinaryFileResponse;

use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Response;

use App\Repositories\FileUploadHelper;

class FileController extends ApiController
{


    /**
     * Store a newly created resource in storage.
     *
     * @param  I\Illuminate\Http\Request  $request
     * @return \lluminate\Http\Response
     */
    public function store(StoreUpdateFileRequest $request, $parent)
    {  

        $fileHelper = FileUploadHelper::create($request,['fileField'=>'file']);

        if(!$fileHelper->fileReady()){
            return $fileHelper->getStatus();
        }

        $file = new File;
        $file->filename = $fileHelper->getFileName();
        $file->fill($request->all());
        $file->save();
        $parent->files()->save($file);

        return $file;
    }



    /**
     * Lets you show a file as long as it is associated with the parent
     * 
     * @param  Model $parent The parent which has this file
     * @param  File $file   The file model which has a file attached
     * @return file response
     */
    public function show($parent, File $file){
        if(!$file->isAssociatedWith($parent)){
            abort(403);
        }

        return $file;
    }

    /**
     * Update a file in storage and version the model
     *
     * @param  I\Illuminate\Http\Request  $request
     * @return \lluminate\Http\Response
     */
    public function update(StoreUpdateFileRequest $request, $parent, File $file)
    {

        //If there is a file attached, this model will be versioned
        if($request->has('file')){
            $fileHelper = FileUploadHelper::create($request,['fileField'=>'file']);

            if(!$fileHelper->fileReady()){
                return $fileHelper->getStatus();
            }

            $file->version($fileHelper);
        }
        
        $file->update($request->all());

        return $file;
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($parent, File $file)
    {
        if(!$file->isAssociatedWith($parent)){
            abort(403,"File not associated with this object");
        }

        $file->delete();
        return $file;
    }


    /**
     * Lets you download a file as long as it is associated with the parent
     * 
     * @param  Model $parent The parent which has this file
     * @param  File $file   The file model which has a file attached
     * @return file response
     */
    public function download($parent, File $file){
        if(!$file->isAssociatedWith($parent)){
            abort(403);
        }

        return response()->download($file->location);
    }


    /**
     * Lets you download a resized image
     *
     * @param  File  $file
     * @return \Illuminate\Http\Response
     */
    public function resize($parent, File $file, $width = 1920, $height = 1080){
        if(!$file->isAssociatedWith($parent)){
            abort(403);
        }

        return $file->resize($width, $height)->response();
    }


}
