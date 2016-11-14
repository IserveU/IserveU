(function() {


	'use strict';

	angular
		.module('iserveu')
		.factory('userPreferenceFactory', ['userPreferenceResource', userPreferenceFactory]);

  	 /** @ngInject */
	function userPreferenceFactory(userPreferenceResource){

		var userPreference = {
			list: {},
      data: {},
			edit: false,
			saving: false,

      initPreferences: function(data) {
        
        this.data = data;
        this.list = [
          {
            label: 'Notify on motion change',
            key: 'motion.notify.user.onchange',
            value: data.motion.notify.user.onchange ? true : false,
            type: 'boolean',
            admin: false
          },
          {
            label: 'notify on motion summary',
            key: 'motion.notify.user.summary',
            value: data.motion.notify.user.summary ? true : false,
            type: 'boolean',
            admin: false
          },
          {
            label: 'admin notify on motion summary',
            key: 'motion.notify.admin.summary',
            value: data.motion.notify.admin.summary ? true : false,
            type: 'boolean',
            admin: true
          },
          {
            label: 'Notify on role change',
            key: 'authentication.notify.user.onrolechange',
            value: data.motion.notify.user.onrolechange ? true : false,
            type: 'boolean',
            admin: false
          },
          {
            label: 'Notify on admin summary',
            key: 'authentication.notify.admin.summary',
            value: data.motion.notify.admin.summary ? true : false,
            type: 'boolean',
            admin: true
          },
          {
            label: 'Notify on admin create',
            key: 'authentication.notify.admin.oncreate',
            value: data.motion.notify.admin.oncreate ? true : false,
            type: 'boolean',
            admin: true
          }
        ];
      },

      getUserPreferences: function(user) {
        userPreferenceResource.getUserPreferences(user).then(function(results){
          this.data = results;
        });
      },

			toggle: function() {
				this.saving = false;
				this.edit = !this.edit;
			},

      // only save admin preferences if the user is admin
			update: function(slug) {
        this.saving = true;


        this.list.forEach(function(el){

            userPreferenceResource.setUserPreference({slug: slug, key: el.key,
              value: el.value ? 1 : 0}).then(successHandler);
     
        });
			}
		}

		function successHandler(res) {
				userPreference.saving = false;
				userPreference.edit = !userPreference.edit;
		}
		return userPreference;
	}

})();
