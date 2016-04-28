<?php

namespace App\Section;

use Illuminate\Database\Eloquent\Model;
use Storage;
use Auth;
use Illuminate\Http\Request;
use App\Http\Requests;

use Illuminate\Validation\ValidationException;


class Section extends Model{


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sections';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order','content','type'
        //,'sectionable_id','sectionable_type' Can't mass assign these
    ];


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at', 'published_at'
    ];

    protected $casts = [
        'content' => 'array'
    ];


    /**************************************** Defined Relationships ****************************************/

    /**
     * Each section is in one article.
     */
    public function article()
    {
        return $this->morphTo();
    }

    public function fillAttributes($section){
        $section['content'] = json_encode($section['content']);
        //        $this->fill(['id'=>$section['id']]);
        foreach($section as $key => $value){
            $this->attributes[$key] = $value;
        }

        return $this;
    }
 
    /**************************************** Custom Methods ****************************************/

    //Basic validation that can be overridden in the child
    public function validateContent(array $content){
        $validator = \Validator::make($content,$this->contentRules);
        
        if($validator->fails()) {
            throw new \Illuminate\Foundation\Validation\ValidationException($validator);
        }

    }


}
