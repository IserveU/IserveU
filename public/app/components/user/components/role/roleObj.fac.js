(function() {


	'use strict';

	angular
		.module('iserveu')
		.factory('roleObj', roleObj);

  	 /** @ngInject */
	function roleObj($stateParams, role, roleService){


		// this should be merged with role.svc.js and role.dir.js

		var roleObj = {
			list: {},
			editRole: false,
			showRoles: function() {
				this.editRole = !this.editRole;
			},
			checkForMatches: function(userRoles) {
				roleService.checkMatchingRoles(this.list, userRoles);
			},
			getAllRoles: function() {
				role.getRoles().then(function(r){
					this.list = r;
				});
			},
			setRole: function(role) {
				roleService.checkIfSelf(role, $stateParams.id);
			}
		}

		roleObj.getAllRoles();

		return roleObj;

	}

})();