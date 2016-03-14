<?php

namespace App\Motion;

use Illuminate\Database\Eloquent\Model;

class Bio extends MotionSection
{
	public function setNameAttribute($value){
        $content                =   $this->content;
        $content['name']        =   $value;
        $this->content          =   $content;
    }

    public function setPositionAttribute($value){
        $content                =   $this->content;
        $content['position']    =   $value;
        $this->content          =   $content;
    }   

    public function setCompanyAttribute($value){
        $content                =   $this->content;
        $content['company']     =   $value;
        $this->content          =   $content;
    }   

	public function setWebsiteAttribute($value){
        $content                =   $this->content;
        $content['website']     =   $value;
        $this->content          =   $content;
    }

    public function setAvatarIdAttribute($value){
        $content                =   $this->content;
        $content['avatar_id']   =   $value;
        $this->content          =   $content;
    }   

    public function setDescriptionAttribute($value){
        $content                =   $this->content;
        $content['description'] =   $value;
        $this->content          =   $content;
    }   


}
