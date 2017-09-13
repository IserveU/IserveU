<?php

namespace App\Repositories\Preferences;

use App\User;
use Validator;

class PreferenceManager
{
    protected $user;

    public $preferences;

    protected $rules = [
      '*'                                           => 'array',
      '*.*'                                         => 'array',
      '*.*.*'                                       => 'array',
      '*.*.*.*'                                     => 'array',
      'authentication.notify.admin.oncreate.on'     => 'boolean',
      'authentication.notify.admin.summary.on'      => 'boolean',
      'authentication.notify.user.onrolechange.on'  => 'boolean',
      'motion.notify.user.onchange.on'              => 'boolean',
      'motion.notify.user.summary.on'               => 'boolean',
      'motion.notify.user.summary.times.*'          => 'integer|nullable|between:0,23',
      'motion.notify.admin.summary.on'              => 'boolean',
    ];

    protected $defaults = [
      'authentication.notify.admin.oncreate.on'     => 1, // Send admins emails when people sign up
      'authentication.notify.admin.summary.on'      => 1, // Send admins a summary of users each day
      'authentication.notify.user.onrolechange.on'  => 0, // Send this user an email if their role has changed
      'motion.notify.user.onchange.on'              => 0, // Send this user an email when a motion they voted on changes
      'motion.notify.user.summary.on'               => 0, // Send this user a summary of the motions on the site
      'motion.notify.user.summary.times'            => ['sunday'=>17, 'monday'=>null, 'tuesday'=>null, 'wednesday'=>null, 'thursday'=>null, 'friday'=>null, 'saturday'=>null],
      'motion.notify.admin.summary.on'              => 0, // Send this user (if admin) an email summarizing the motions
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
     * Remove a preference in the preferences array.
     *
     * @param string $key Key in the dot notation
     */
    public function exists($key)
    {
        if (array_has($this->preferences, $key)) {
            return true;
        }

        return false;
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
        //These preference migrations can't work on some older users
        if ($this->exists($oldName)) {
            $value = $this->getPreference($oldName);
            $this->removePreference($oldName);

            $this->createPreference($newName, $value, true);
        }

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

        $this->stagePreferences();

        return $this;
    }

    /**
     * Validates and moves the preferences to the user model for saving.
     *
     * @return User the user with preferences staged
     */
    public function stagePreferences()
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

        return $this->user;
    }

    /**
     * Commits the preferences to the user.
     *
     * @return $this
     */
    public function save()
    {
        $this->stagePreferences()
              ->save();
    }
}
