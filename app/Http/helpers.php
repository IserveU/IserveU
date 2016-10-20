<?php
    function AddRequired($rules, $requireds)
    {
        foreach ($requireds as $required) {
            $rules[$required] = $rules[$required].'|required';
        }

        return $rules;
    }

    function AddRule($existingRules, $targets, $rule)
    {
        foreach ($targets as $target) {
            $existingRules[$target] = $existingRules[$target].'|'.$rule;
        }

        return $existingRules;
    }

    function getTypeFromValidator($validation)
    {
        if (strpos($validation, 'password') !== false) {
            return 'password';
        }

        if (strpos($validation, 'boolean') !== false) {
            return 'md-switch';
        }

        if (strpos($validation, 'string') !== false) {
            return 'input';
        }

        if (strpos($validation, 'email') !== false) {
            return 'email';
        }

        return 'input';
    }

    /**
     *   Format the date into human readable and difference for frontend.
     *
     *   @param string $date
     *
     *   @return array
     */
    function formatIntoReadableDate($date)
    {
        if ($date === null) {
            return $date;
        }

        $carbon = \Carbon\Carbon::parse($date);

        return [
            'diff'          => $carbon->diffForHumans(),
            'alpha_date'    => $carbon->format('j F Y'),
            'carbon'        => $carbon,
        ];
    }
