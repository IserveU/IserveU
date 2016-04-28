<?php

namespace App\Section;

use Illuminate\Database\Eloquent\Model;

use Input;
use Auth;
use App\File;
use Storage; 

class InlineImage extends Section
{


    protected $appends = array(
        'images'
    );

    protected $attributes = array(
        'type' => 'InlineImage'
    );

    public static function boot(){
        parent::boot();


        static::deleted(function($model){
            foreach($model->images as $image){
              $image->file->delete();
            }
            return true;
        });
      
        return true;

    }


    /**************************************** Custom Methods ****************************************/

    protected $contentRules = [
        '*.image.file'          =>    "image",
        '*.image.description'   =>    "min:1",
        '*.image.user_id'       =>    "exists:users,id",
        '*.file_id'             =>    "exists:files,id",
        "*.url"                 =>    "url"
    ];

    /*************************
    *   
    *  Sets the section content, expects an array 
    *  matching the content rules
    *
    ***************************/

    //Move this up top afterwardds
    public function setContentAttribute(array $content){
        $contentAttribute = [];
        $this->validateContent($content);

        // if(!array_key_exists('image',$content[0])){
        //     return true;
        // }
        // echo var_dump($content);
        // echo "\n\n ====================";

        foreach($content as $key => $image){

            $imageJson = array_filter(
                $image,
                function ($key) {
                    return in_array($key, ['url','file_id']);
                },
                ARRAY_FILTER_USE_KEY
            );

           // echo var_dump($image['file']);
            if(array_key_exists('image',$image)){
                \Log::info('Adding or updating a file on an image');
                //Upload the files

                $file = File::updateOrCreate([
                    'id'    =>  array_key_exists('file_id',$image)?$image['file_id']:null
                ],
                $image['image']);

                $imageJson['file_id'] = $file->id;
            }

            $imageJson['url'] = array_key_exists('url', $image)?$image['url']:'';

            $contentAttribute[] = $imageJson;

            //Set the ids for the files in the content array
        }

        $this->attributes['content'] = json_encode($contentAttribute);
    }



    public function getImageIdsAttribute(){
        $ids = [];
        $content = json_decode($this->attributes['content'],true);
        foreach($content as $image){
            $ids[] = $image['file_id'];
        }
        return $ids;
    }

    /**************************************** Defined Relationships ****************************************/
    
    public function getImagesAttribute()
    {
        $files = File::findMany($this->imageIds);
        $content = json_decode($this->attributes['content'],FALSE);
        foreach($content as $image){
            foreach ($files as $file) {
                if($image->file_id == $file->id){
                    $image->file  = $file;
                }
            }
            $images[] = $image;
        }

        return isset($images)?$images:[];
    }

}
