<?php

namespace App\Repositories\Preferences;

use PreferenceManager;

/**
 *   A reusable status and published trait to manage visibility of montions and users.
 **/
trait Preferenceable
{
    /**
       * Sets a preference in the preferences array.
       *
       * @param string         $key   Key in the dot notation
       * @param String/Integer $value The value to set the key to be
       * @param bool           $force If you wish to set a value
       */
      public function setPreference($key, $value, $force = false)
      {
          (new PreferenceManager($this))->setPreference($key, $value, $force = false)->save();
      }

      /**
       * Gets a preference in the preferences array of this model.
       *
       * @param string $key Key in the dot notation
       *
       * @return Value of the preference
       */
      public function getPreference($key)
      {
          return (new PreferenceManager($this))->getPreference($key);
      }
}
