(function () {

  'use strict';

  angular
    .module('iserveu')
    .factory('userPreferenceFactory', ['userPreferenceResource', userPreferenceFactory]);

  /** @ngInject */
  function userPreferenceFactory (userPreferenceResource) {
    var userPreference = {
      sections: {},
      data: {},
      edited: [],

      initPreferences: function(data) {
        this.data = data;

        /* Collection of once daily summary emails */
        this.sections.summaries = {
          label:  'Summaries',
          icon:   'mdi-chart-areaspline',
          data:   'Change my summary email preferences',
          edit:   false,
          saving: false,
          list: [
            {
              label: 'Motion Summary',
              tooltip: 'Email me a summary of the motions opening and closing',
              key: 'motion.notify.user.summary.on',
              value: data.motion.notify.user.summary.on ? true : false,
              type: 'boolean',
              permission: ""
            },
            {
              label: 'Submitted Motion Summary', // Show motions
              tooltip: 'Email me a summary of submitted motions to be approved',
              key: 'motion.notify.admin.summary.on',
              value: data.motion.notify.admin.summary.on ? true : false,
              type: 'boolean',
              permission: "administrate-motion"
            },
            {
              label: 'New User Summary', // When
              key: 'authentication.notify.admin.summary.on',
              tooltip: 'Email me a summary of the new users who signed up to the site',
              value: data.authentication.notify.admin.summary.on ? true : false,
              type: 'boolean',
              permission: "administrate-user"
            }
          ]
      };

        /* Collection of instant notifications when events occur */
      this.sections.notifications = {
        label:  'Notifications',
        icon:   'mdi-message-alert',
        data:   'Change what I am notified about',
        edit:   false,
        saving: false,
        list: [
          {
            label: 'A Motion Changes',
            key: 'motion.notify.user.onchange.on',
            tooltip: 'Send me a message whenever a motion I voted on changes',
            value: data.motion.notify.user.onchange.on ? true : false,
            type: 'boolean',
            permission: "create-vote"
          },
          {
            label: 'My Account Is Upgraded/Downgraded',
            tooltip: 'Send me a message when my account status changes',
            key: 'authentication.notify.user.onrolechange.on',
            value: data.authentication.notify.user.onrolechange.on ? true : false,
            type: 'boolean',
            permission: ""
          },
          {
            label: 'A User Is Created',
            tooltip: 'Send me a message whenever a user signs up',
            key: 'authentication.notify.admin.oncreate.on',
            value: data.authentication.notify.admin.oncreate.on ? true : false,
            type: 'boolean',
            permission: "administrate-user"
          }
        ]
      }
    },

      getUserPreferences: function(user) {
        userPreferenceResource.getUserPreferences(user).then(function(results){
          this.data = results;
        });
      },

      toggle: function() {
        this.saving = false;
        this.editing = !this.editing;
      },

      // only save admin preferences if the user is admin
      save: (slug, item) => {
        this.saving = true;
        if (!this.edited) {
          return;
        }
        saveChanges(slug, this.edited, item);
        this.edited = [];
      },

      change: (pref) => {
        if (!this.edited) {
          this.edited = []
        }
        var keyIndex = this.edited.findIndex(i => i.key === pref.key);
        if (keyIndex >= 0) {
          this.edited[keyIndex] = {key: pref.key, value: pref.value ? 1 : 0};
        } else {
          this.edited.push({key: pref.key, value: pref.value ? 1 : 0});
        }
      }
    }

    function successHandler(item) {
      item.saving = false;
      item.edit = false;
    }

    function saveChanges(slug, prefs, item) {
      if (prefs.length === 0) {
        return successHandler(item);
      }
      let pref = prefs.pop();
      userPreferenceResource
      .setUserPreference({slug: slug, key: pref.key, value: pref.value})
      .then(() => {
        saveChanges(slug, prefs, item)
      });
    }

  return userPreference;
  }

})();
