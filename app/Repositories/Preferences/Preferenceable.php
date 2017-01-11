<?php

namespace App\Repositories\Preferences;

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
       */
      public function setPreference($key, $value)
      {
          (new PreferenceManager($this))->setPreference($key, $value)
                                        ->stagePreferences();

          return $this;
      }

        /**
         * Sets a preference in the preferences array.
         *
         * @param string         $key   Key in the dot notation
         * @param String/Integer $value The value to set the key to be
         */
        public function createPreference($key, $value, $force = false)
        {
            (new PreferenceManager($this))->createPreference($key, $value, $force)
                                          ->stagePreferences();

            return $this;
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
