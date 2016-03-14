<?php

namespace App\Motion;

use Illuminate\Database\Eloquent\Model;

class Budget extends MotionSection
{
	public function setPriceAttribute($value){
        $content                =   $this->content;
        $content['price']       =   $value;
        $this->content          =   $content;
    }

    public function setMotionIdAttribute($value){
        $this->attributes['motion_id'] = 1;
        return true;
    }
}
