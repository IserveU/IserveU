(function() {


	'use strict';

	angular
		.module('app.user')
		.factory('userPreferenceFactory', ['UserPreferenceResource', userPreferenceFactory]);

  	 /** @ngInject */
	function userPreferenceFactory(UserPreferenceResource){

		var userPreference = {
			sections: {},
      data: {},

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
                  value: data.motion.notify.admin.summary.on ? true : false,
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
        UserPreferenceResource.getUserPreferences(user).then(function(results){
          this.data = results;
        });
      },

			toggle: function() {
				this.saving = false;
				this.edit = !this.edit;
			},

      // only save admin preferences if the user is admin
			update: function(slug, pref) {
        this.saving = true;

        UserPreferenceResource.setUserPreference({slug: slug, key: pref.key,
          value: pref.value ? 1 : 0}).then(successHandler);
			}
		}

		function successHandler(res) {
				userPreference.saving = false;
				userPreference.edit = !userPreference.edit;
		}
		return userPreference;
	}

})();
