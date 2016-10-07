(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('userManager', ['user', userManager]);
		// TODO: refactor the CSS of the template.
	/** @ngInject */
	function userManager(user) {

		function userController() {

			var self = this;

			self.users = {};

			user.getIndex().then(function(r) {
				self.users = r.data;
			}, function(e) { console.log(e); });

		};


		return {
			controller: userController,
			controllerAs: 'user',
			templateUrl: 'app/components/admin.dash/user/user-manager.tpl.html',
		}


	}


})();