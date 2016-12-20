<?php

namespace App;

use Auth;
use Carbon\Carbon;
use Hyn\Tenancy\Abstracts\Models\TenantModel;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class ApiModel extends TenantModel
{
    public $errors;

    public $rulesParsed = false;

    protected $adminVisible = [];
    protected $adminFillable = [];

    public function getCreatedAtAttribute($attr)
    {
        $carbon = Carbon::parse($attr);

        return [
            'diff'          => $carbon->diffForHumans(),
            'alpha_date'    => $carbon->format('j F Y'),
            'carbon'        => $carbon,
        ];
    }

    public function getUpdatedAtAttribute($attr)
    {
        $carbon = Carbon::parse($attr);

        return [
            'diff'          => $carbon->diffForHumans(),
            'alpha_date'    => $carbon->format('j F Y'),
            'carbon'        => $carbon,
        ];
    }

    public function validate()
    {
        $validator = Validator::make($this->getAttributes(), $this->getRulesAttribute());
        if ($validator->fails()) {
            $this->errors = $validator->messages();

            return false;
        }

        return true;
    }

    public function getFieldsAttribute()
    {
        $result = [];

        $values = $this->toArray(); //Ensures security

        foreach ($this->getFillableAttribute() as $key) {
            if (!empty($this->fields[$key])) {
                $field = [
                    'key'               => $key,
                    'type'              => $this->fields[$key]['type'],
                    'templateOptions'   => [
                        'valueProp'         => isset($values[$key]) ? $values[$key] : null,
                        'required'          => (strpos('required', $this->rules[$key])) ? true : false,
                        'label'             => $this->fields[$key]['label'],
                        'rules'             => $this->rules[$key],
                    ],
                ];
                $result[] = $field;
            }
        }

        return $result;
    }

    public function getAlteredLockedFields()
    {
        $dirty = $this->getDirty();
        $changed = [];
        foreach ($this->locked as $key) {
            if (array_key_exists($key, $dirty)) {
                array_push($changed, $key);
            }
        }

        return $changed;
    }

    public function secureFill(array $input)
    {
        $this->getFillableAttribute();

        return parent::fill($input);
    }

    public function getFillableAttribute()
    {
        if (Auth::user()->can('administrate-'.$this->table)) { //Admin
            $this->fillable = array_unique(array_merge($this->adminFillable, $this->fillable));
        }

        return $this->fillable;
    }

    public function getVisibleAttribute()
    {
        if (!Auth::check()) {
            return $this->visible;
        }

        if (Auth::user()->can('show-'.$this->table)) { //Admin
            $this->visible = array_unique(array_merge($this->adminVisible, $this->visible));
        }

        if (array_key_exists('user_id', $this->attributes)) { //The person who created this
            $this->visible = array_unique(array_merge($this->creatorVisible, $this->visible));
        }

        return $this->visible;
    }

    public function toJson($options = 0)
    {
        $this->getVisibleAttribute();

        return parent::toJson();
    }

    public function toArray()
    {
        $this->getVisibleAttribute();

        return parent::toArray();
    }

    public function getRulesAttribute()
    {
        if ($this->rulesParsed) {
            return $this->rules; //Prevents 'required' and update IDS being added many times
        }

        if ($this->id) { //Existing record

            foreach ($this->unique as $value) { //Stops the unique values from ruining everything
                $this->rules[$value] = $this->rules[$value].','.$this->id;
            }

            $this->rules = AddRule($this->rules, $this->onUpdateRequired, 'required'); //Need to require things after appending the ID
        }

        if (Request::method() == 'POST' || Request::method() == 'GET') { //Initial create
            $this->rules = AddRule($this->rules, $this->onCreateRequired, 'required');
            //return $this->rules; //DOn't add on things that aren't actual validation rules
        }

        $this->rulesParsed = true;

        return $this->rules;
    }
}
