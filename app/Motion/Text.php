<?php

namespace App\Motion;

use Illuminate\Database\Eloquent\Model;

class Text extends MotionSection
{
	public function setTitleAttribute($value){
        $content                =   $this->content;
        $content['title']       =   $value;
        $this->content          =   $content;
    }

    public function setTextAttribute($value){
        $content                =   $this->content;
        $content['text']        =   $value;
        $this->content          =   $content;
    }   

}
