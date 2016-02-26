(function() {

	'use strict';

	angular
		.module('iserveu')
		.service('roleService', roleService);

	// TODO: Refactor this whole thing.
  	 /** @ngInject */
	function roleService(role, user, ToastMessage, refreshLocalStorage) {

		this.grant = grant;
		this.remove = remove;
		this.checkIfSelf = checkIfSelf;
		this.checkMatchingRoles = checkMatchingRoles;

		function grant(role, id){
			role.grantRole({
				id: id,
				role_name: role}).then(function(){
					refreshLocalStorage.init();
			});
		}

		function remove(role_id, id){
			role.deleteUserRole({
				id: id,
				role_id: role_id}).then(function(){
					refreshLocalStorage.init();
			});
		}

		function checkMatchingRoles(roles, user_roles) {
			angular.forEach(roles, 
				function(role, key){
				
				role["ifUserHasRole"] = false;
				
				angular.forEach(user_roles, function(r, key){
					if(role.display_name == r)
						role["ifUserHasRole"] = true;
				});
			
			});
		}

		// TODO: make this into an UNDO
		function checkIfSelf(role, id){
			if(id === user.self.id)

				ToastMessage.customFunction(
					"Are you sure you want to modify your own role?", 
				    "Yes", updateSelfRole( role, id )
				);
			else 
				updateUserRole(role, id);
		}

		function updateSelfRole(role, my_id){
			if(role.ifUserHasRole)
				grant(role.name, my_id);
			else if (!role.ifUserHasRole)
				remove(role.id, my_id);
		}

		function updateUserRole(role, user_id){
			if(!role.ifUserHasRole)
				grant(role.name, user_id);
			else if (role.ifUserHasRole)
				remove(role.id, user_id);
		}

	}



}());
