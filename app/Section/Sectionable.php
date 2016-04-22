<?php

namespace App\Section;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


trait Sectionable{


    /************************* Custom Functions ***********************************/

    protected $sectionsWillBe   = [];
    protected $currentSectionIds = [];

    public static function bootSectionable(){
        static::created(function($model){

            if(!$model->currentSectionIds){
                $model->sections = $model->sectionsWillBe;
            }
            return true;
        });

        static::updated(function($model){
            if(!$model->currentSectionIds){
                $model->sections = $model->sectionsWillBe;
            }
            return true;
        });


        static::deleted(function($model){
            foreach($model->typedSections as $section){
                $section->delete();                
            }
            return true;
        });
    }


    /**************************************** Data Mutators ****************************************/



    public function setSectionsAttribute(Array $sections){
        \Log::info('trying to set sections');

        if(!$this->getAttribute('id')){

            $this->sectionsWillBe = $sections;
            return false;
        }

        foreach($sections as $section){


            $validator = \Validator::make($section,[
                'id'        =>      'exists:sections,id',
                '_method'   =>      'alpha_dash',
                'content'   =>      'array',
                'order'     =>      'digits_between:0,100',
                'type'      =>      'required|alpha_dash'
            ]);

            if($validator->fails()) {
                throw new \Illuminate\Foundation\Validation\ValidationException($validator);
            }

            if(!array_key_exists('type',$section)){
                abort(422,'Section must have a type set');
            }

            $class = "\App\Section\\".$section['type'];

            if(!$this->deleteSection($section,$class)){
	          
	            if(array_key_exists('id',$section)){
	    
		            $section = $class::updateOrCreate(
		                [
		                    'id'=>  $section['id']
		                ],
		                $section
		            );
	              
	            } else {
	                $section = $class::create($section);
	            }

	            $section->sectionable_type = get_class($this);
	            $section->sectionable_id   = $this->getAttribute("id");
	            $section->save();



	            \Log::info('adding a section '.$section->id);

	            $this->currentSectionIds[] = $section->id;
	        }
	        	
        }

        $this->touch();

        return true;
    }

    public function deleteSection($section,$class){
        if(array_key_exists('_method',$section) && $section["_method"] == 'DELETE'){        	
            $class::find($section['id'])->delete();
            return true;
        }

        return false;
    }



    /**************************************** Defined Relationships ****************************************/

    /**
     * Get the sections included in the article.
     */
    public function sections()
    {
        return $this->morphMany('App\Section\Section','sectionable')->orderBy('order');
    }

    public function getTypedSectionsAttribute(){
        $typedSections = new \Illuminate\Database\Eloquent\Collection;

        foreach($this->sections as $section){
            $class = "\App\Section\\".$section['type'];
            //Hydrate method overrode the content setter
            $typedSections->add($class::find($section->id));
        }

        return $typedSections;
    }

}
