(function() {

	'use strict';

	angular
		.module('iserveu')
		.service('roleService', roleService);

		// TODO: another abstract if possible; things seem pretty messy here
		// this is all business logic though, keep that in mind; it might
		// just be like that?

	function roleService($mdToast, role, user, ToastMessage, refreshLocalStorage) {

		var vm = this;

		//TODO: angular.extend on these :) 
		vm.grant = grant;
		vm.remove = remove;
		vm.checkIfNew = checkIfNew;
		vm.checkMatchingRoles = checkMatchingRoles;

		function grant(role_name, user_id){
			role.grantRole({
				user_id: user_id,
				role_name: role_name}).then(function(){
					refreshLocalStorage.init();
			});
		}

		function remove(role_id, user_id){
			role.deleteUserRole({
				user_id: user_id,
				role_id: role_id}).then(function(){
					refreshLocalStorage.init();
			});
		}

		function checkMatchingRoles(roles, this_users_roles) {
			angular.forEach(roles, function(role, key){
				role["ifUserHasRole"] = false;
				angular.forEach(this_users_roles, function(this_role, key){
					if(role.display_name == this_role){
						role["ifUserHasRole"] = true;
					}
				})
			});
		}

		// TODO: make this into an UNDO
		function checkIfNew(role, id){
			if(id == user.self.id){
				var toast = ToastMessage.action("Are you sure you want to modify your own role?", "Yes");
				$mdToast.show(toast).then(function(response){
					if(response == 'ok'){
						editSelfRole(role, id);
					}
				});
			} else {
				editUserRole(role, id);
			}
		}

		function editSelfRole(role, my_id){
			if(role.ifUserHasRole){
				grant(role.name, my_id);
			} else if (!role.ifUserHasRole){
				remove(role.id, my_id);
			}
		}

		function editUserRole(role, user_id){
			if(!role.ifUserHasRole){
				grant(role.name, user_id);
			} else if (role.ifUserHasRole){
				remove(role.id, user_id);
			}
		}

	}



}());
