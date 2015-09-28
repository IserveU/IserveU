<?php
    function AddRequired($rules,$requireds){
        foreach($requireds as $required){
            $rules[$required] = $rules[$required]."|required";
        }
        return $rules;
    }
    
    function AddRule($existingRules = array(),$targets = array(),$rule){
        foreach($targets as $target){
            $existingRules[$target] = $existingRules[$target]."|".$rule;
        }
        return $existingRules;
    }

    function getTypeFromValidator($validation){
        if(strpos($validation,'password') !== false){
            return "password";
        }

        if(strpos($validation,'boolean') !== false){
            return "md-switch";
        }

        if(strpos($validation,'string') !== false){
            return "input";
        }

        if(strpos($validation,'email') !== false){
            return "email";
        }

        return 'input';

    }


?>