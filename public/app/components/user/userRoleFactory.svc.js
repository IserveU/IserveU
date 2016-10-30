(function() {


	'use strict';

	angular
		.module('iserveu')
		.factory('userRoleFactory', ['userRoleResource', userRoleFactory]);

  	 /** @ngInject */
	function userRoleFactory(userRoleResource){

		var UserRole = {
			list: {},
			edit: false,
			saving: false,

			toggle: function() {
				this.saving = false;
				this.edit = !this.edit;
			},

			check: function(d) {
				for(var i in d) {
					if (d[i]) {
						for(var j in this.user)
							if(d[i].display_name === this.user[j])
								this.list[i]['hasRole'] = true;
					}
				}
			},

			getAllRoles: function() {
				userRoleResource.getRoles().then(function(results){
					UserRole.list = results.data;
				});
			},

			update: function(user, originalRoles) {
				UserRole.saving = true;

				var ogRoles = [],
					  deleteDiff = [];
				angular.forEach(originalRoles, function(el) {
					ogRoles.push(el.name);
				});

				var toPost = user.roles.filter(function(x) {
					if (ogRoles.indexOf(x) < 0 ) {
						return true;
					} else {
						deleteDiff.push(x);
					}
				});

			 	var toDelete = ogRoles.filter(function(y) {
					return deleteDiff.indexOf(y) < 0;
				});

			 	toPost.forEach(function(el) {
			 		UserRole.grant({slug: user.slug, name: el});
			 	});
			 	toDelete.forEach(function(el) {
			 		UserRole.remove({slug: user.slug, name: el});
			 	});
			},

			grant: function (data){
				userRoleResource
					.grantRole(data)
					.then(successHandler);
			},

			remove: function (data){
				userRoleResource
					.deleteUserRole(data)
					.then(successHandler);
			}
		}

		UserRole.getAllRoles();

		function successHandler(res) {
				UserRole.saving = false;
				UserRole.edit = !UserRole.edit;
		}

		return UserRole;

	}

})();