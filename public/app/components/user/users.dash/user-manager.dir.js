(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('userManager', ['UserResource', 'UserRoleResource', userManager]);

	function userManager(UserResource, UserRoleResource) {

		function userController() {

			var self = this;

			self.users = {};

			self.getUserRole = function(user) {
				UserRoleResource.getUserRole(user.slug).then(function(results) {
					var roles = results.data || results;
					user.roles = '';
					if (roles.length > 0)
						for (var i in roles)
							if(roles[i])
								user.roles += roles[i].display_name+' ';
				});
			};

			UserResource.getIndex().then(function(r) {
				self.users = r.data;
			}, function(e) { console.log(e); });

		}


		return {
			controller: userController,
			controllerAs: 'user',
			templateUrl: 'app/components/user/users.dash/user-manager.tpl.html'
		}


	}


})();
