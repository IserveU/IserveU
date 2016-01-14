(function() {


	'use strict';

	angular
		.module('iserveu')
		.factory('roleObj', roleObj);

	function roleObj($stateParams, role, roleService){

		var roleObj = {
			list: {},
			editRole: false,
			showRoles: function() {
				roleObj.editRole = !roleObj.editRole;
			},
			checkForMatches: function(userRoles) {
				roleService.checkMatchingRoles(roleObj.list, userRoles);
			},
			getAllRoles: function() {
				role.getRoles().then(function(r){
					roleObj.list = r;
				});
			},
			setRole: function(role) {
				roleService.checkIfNew(role, $stateParams.id);
			}
		}

		roleObj.getAllRoles();

		return roleObj;

	}

})();