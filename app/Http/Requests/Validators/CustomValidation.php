<?php

class CustomValidation
{
    public function notRequired($field, $value, $parameters)
    {
        return false;
    }

    public function is_valid_json_value($field, $value, $parameters)
    {
        if (is_bool($value) ||
            is_array($value) ||
            is_string($value) ||
            is_numeric($value)) {
            return true;
        } else {
            return false;
        }
    }
}
