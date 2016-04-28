<?php

namespace App\Section;

use Illuminate\Database\Eloquent\Model;

use Input;
use Auth;
use App\File;
use Storage; 

class Profile extends Section
{
 
    protected $appends = array(
        'image'
    );

    protected $attributes = array(
        'type' => 'Profile'
    );

    public static function boot(){
        parent::boot();

        static::creating(function($model){

        });

        static::created(function($model){

        });

        static::deleted(function($model){

            if($model->image){
              $model->image->delete();
            }
            return true;
        });
       
        return true;
    }


    /**************************************** Custom Methods ****************************************/

    protected $contentRules = [
        'image.file'           =>     "image",
        'image.description'    =>     "min:1",
        'image.user_id'        =>     "exists:users,id",
        'text'                 =>     "required",
        "heading"              =>     "max:150",
        "subheading"           =>     "max:255"
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

        $contentJson = array_filter(
            $content,
            function ($key) {
                return in_array($key, ['text','heading','subheading','file_id']);
            },
            ARRAY_FILTER_USE_KEY
        );

       // echo var_dump($image['file']);
        if(array_key_exists('image',$content)){
            \Log::info('Adding or updating a file on an image');
            //Upload the files
            $file = File::updateOrCreate([
                'id'    =>  array_key_exists('file_id',$content)?$content['file_id']:null
            ],
            $content['image']);

            $contentJson['file_id'] = $file->id;
        }


        //Set the ids for the files in the content array
        $this->attributes['content'] = json_encode($contentJson);
    }

    public function getHeadingAttribute(){
        return array_key_exists("heading",$this->content)?$this->content['heading']:"";
    }

    public function getSubheadingAttribute(){
        return array_key_exists("subheading",$this->content)?$this->content['subheading']:"";
    }

    public function getTextAttribute(){
        return array_key_exists("text",$this->content)?$this->content['text']:"";
    }

    
    /**************************************** Defined Relationships ****************************************/
    
    public function getImageAttribute()
    {
        return File::find($this->content['file_id']);
    }


}
