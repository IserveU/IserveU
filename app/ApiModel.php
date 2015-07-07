<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Role;
use Auth;
use Illuminate\Support\Facades\Validator;


class ApiModel extends Model
{
    public $errors; 

    protected $adminVisible = [];
    protected $adminFillable = [];


    public function validate(){
        $validator = Validator::make($this->getAttributes(),$this->rules);
        if($validator->fails()){
            $this->errors = $validator->messages();
            return false;
        }
        return true;
    }

    public function getFields(){
        $result = [];

        $values = $this->toArray(); //Ensures security
        foreach($this->fillable as $key){
            $field = [
                'name'              =>  $key,
                'rules'             =>  $this->rules[$key],
                'type'              =>  $this->fields[$key]['type'],
                'templateOptions'   =>  [
                    'valueProp'         =>  isset($values[$key])?$values[$key]:null,
                    'required'          =>  (strpos('required',$this->rules[$key]))?true:false,
                    'label'             =>  $this->fields[$key]['label'],
                ]
            ];
            $result[] = $field;
        }
        return $result;
    }

    public function getLockedAttribute(){
        if(Auth::user()->can('edit-'.$this->table)){
            $this->locked = [];
        }
        return $this->locked;
    }

    public function getAlteredLockedFields(){
        $dirty = $this->getDirty();
        $changed = array();
        foreach($this->locked as $key){
            if(array_key_exists($key,$dirty)){
                array_push($changed,$key);
            }
        }
        return $changed;
    }

    public function secureFill(array $input){
        //Default fill doesn't use these accesstors
        $this->getFillableAttribute(); 
        $this->getRulesAttribute(); 
        return parent::fill($input);
    }

    public function getFillableAttribute(){
        if(Auth::user()->can("edit-".$this->table)){
            $this->fillable = array_unique(array_merge($this->adminFillable, $this->fillable));
        }
        return $this->fillable;
    }

    public function getVisibleAttribute(){
        if(Auth::user()->can("show-".$this->table)){
            $this->visible = array_unique(array_merge($this->adminVisible, $this->visible));
        }
        return $this->visible;
    }

    public function toJson($options = 0){
        $this->getVisibleAttribute();
        return parent::toJson();
    }

    public function toArray() {
        $this->getVisibleAttribute();
        return parent::toArray();
    }   

    public function getRulesAttribute(){
        if($this->id){ //Existing record            
            $this->rules = AddRule($this->rules,$this->onUpdateRequired,'required');

            foreach($this->unique as $value){ //Stops the unique values from ruining everything
                $this->rules[$value] = $this->rules[$value].",".$this->id;
            }

            if(Request::method()=="PATCH"){ // Adds things that aren't actual validation rules if this is the actual patch
                return $this->rules;    
            }
        }

        if(Request::method()=="POST" || Request::method()=="GET"){ //Initial create
            $this->rules = AddRule($this->rules,$this->onCreateRequired,'required');
            return $this->rules; //DOn't add on things that aren't actual validation rules
        }
        return $this->rules;    
    }

}
