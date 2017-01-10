<?php

namespace App\Repositories\Preferences;

use App\User;
use Validator;

class PreferenceManager
{
    protected $user;

    public $preferences;

    protected $rules = [
      'authentication.notify.admin.oncreate.on'     => 'boolean',
      'authentication.notify.admin.summary.on'      => 'boolean',
      'authentication.notify.user.onrolechange.on'  => 'boolean',
      'motion.notify.user.onchange.on'              => 'boolean',
      'motion.notify.user.summary.on'               => 'boolean',
      'motion.notify.user.summary.frequency'        => 'cron_expression', //Figure out this
      'motion.notify.admin.summary.on'              => 'boolean',
    ];

    protected $defaults = [
      'authentication.notify.admin.oncreate.on'     => 1,
      'authentication.notify.admin.summary.on'      => 1,
      'authentication.notify.user.onrolechange.on'  => 1,
      'motion.notify.user.onchange.on'              => 0,
      'motion.notify.user.summary.on'               => 0,
      'motion.notify.user.summary.frequency'        => '0 18 * * 0',  //Sunday @ 6pm
      'motion.notify.admin.summary.on'              => 0,
    ];

    /**
     * Create a new instance.
     *
     * @param array $attributes
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->preferences = $user->preferences;
    }

    /**
     * Sets a preference in the preferences array.
     *
     * @param string         $key   Key in the dot notation
     * @param String/Integer $value The value to set the key to be
     *
     * @return $this
     */
    public function setPreference($key, $value)
    {
        if (!array_has($this->preferences, $key)) {
            throw new \Exception('Preference key does not exist');
        }
        array_set($this->preferences, $key, $value);

        return $this;
    }

    /**
     * If you wish to set a value that does not exist.
     *
     * @param string         $key   Key in the dot notation
     * @param String/Integer $value The value to set the key to be
     *
     * @return $this
     */
    public function createPreference($key, $value, $overwrite = false)
    {
        if (!$overwrite && array_has($this->preferences, $key)) {
            throw new \Exception('Preference already exists and will be owerwritten');
        }

        array_set($this->preferences, $key, $value);

        return $this;
    }

    /**
     * Gets a preference in the preferences array.
     *
     * @param string $key Key in the dot notation
     *
     * @return Value of the preference
     */
    public function getPreference($key)
    {
        if (!array_has($this->preferences, $key)) {
            throw new \Exception('Preference key does not exist');
        }

        return array_get($this->preferences, $key);
    }

    /**
     * Remove a preference in the preferences array.
     *
     * @param string $key Key in the dot notation
     */
    public function removePreference($key)
    {
        if (!array_has($this->preferences, $key)) {
            throw new \Exception('The preference key does not exist to remove');
        }
        array_pull($this->preferences, $key);

        return $this;
    }

    /**
     * Rename a single preference.
     *
     * @param string $oldName The current name
     * @param string $newName The new name
     *
     * @return $this
     */
    public function renamePreference($oldName, $newName)
    {
        $value = $this->getPreference($oldName);
        $this->removePreference($oldName);

        $this->createPreference($newName, $value, true);

        return $this;
    }

    public function renamePreferences($oldNewPairs)
    {
        foreach ($oldNewPairs as $old => $new) {
            $this->renamePreference($old, $new);
        }

        return $this;
    }

    /**
     * Sets default values without wiping things that were already set.
     */
    public function setDefaults()
    {

      // $this->preferences = array_merge_recursive($this->defaults,$this->preferences); // For practicality it's way easier to read dot notation

      foreach ($this->defaults as $key => $value) {
          if (!array_has($this->preferences, $key)) {
              $this->createPreference($key, $value, false);
          }
      }

        $this->user->preferences = $this->preferences;

        return $this;
    }

    /**
     * Commits the preferences to the user.
     *
     * @return $this
     */
    public function save()
    {
        $validator = Validator::make($this->preferences, $this->rules);

        if (\App::runningInConsole()) {
            // Getting laravel to do nice CLI validation is hard
            if ($validator->fails()) {
                abort(400, $validator->getMessageBag());
            }
        }

        $validator->validate();

        $this->user->preferences = $this->preferences;

        $this->user->save();
    }
}
