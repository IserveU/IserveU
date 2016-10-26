<?php

namespace App;

use anlutro\LaravelSettings\Facade;

class Setting extends Facade
{
    public function __construct()
    {
        parent::__construct();
    }

    public static function save()
    {
        parent::save();
    }

    public static function forgetAll()
    {
        parent::forgetAll();
    }

    public static function set($key, $value)
    {
        parent::set($key, $value);
    }

    public static function all()
    {
        return parent::all();
    }

    public static function setPath($path)
    {
        parent::setPath($path);
    }

    // for the API
    public static function update($key, $value)
    {
        if (is_array($value)) {
            foreach ($value as $nestedKey => $value) {
                static::update($key.'.'.$nestedKey, $value);
            }
        }

        if (!is_null(self::get($key))) {
            parent::set($key, $value);
            parent::save();

            return true;
        } else {
            return false;
        }
    }

    /**
     * Checks if a setting exists and if not create it.
     *
     *
     * @param string $key   Key of the setting
     * @param string $value Value of the setting to set
     *
     * @return void
     */
    public static function ifNotSetThenSet($key, $value)
    {
        if (is_array($value)) {
            foreach ($value as $nestedKey => $value) {
                static::ifNotSetThenSet($key.'.'.$nestedKey, $value);
            }
        }

        if (is_null(self::get($key))) {
            Parent::set($key, $value);
            Parent::save();
        }
    }

    /**
     * Rename the key of an existing setting.
     *
     *
     * @param string $oldName The setting you want to rename
     * @param string $newName The name you want to give it
     *
     * @return void
     */
    public static function renameSetting($oldName, $newName)
    {
        if (!is_null(self::get($oldName))) {
            Parent::set($newName, self::get($oldName));
            Parent::forget($oldName);
            Parent::save();
        }
    }
}
