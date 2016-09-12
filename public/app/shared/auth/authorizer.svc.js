(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.service('Authorizer', [
			'$rootScope', 
		Authorizer]);
/**
*	http://adamalbrecht.com/2014/09/22/authorization-with-angular-and-ui-router/
*/
	function Authorizer($rootScope) {

		this.canAccess = function(requirePermissions) {

			return true;

			var user = $rootScope.authenticatedUser;

			if(!user || angular.isUndefined(requirePermissions))
				return false;

			requirePermissions = !angular.isArray(requirePermissions) ? [requirePermissions] : requirePermissions;

			return requirePermissions.every(function(el){
				return user.permissions.includes(el.trim());
			});
		}

	}

})();