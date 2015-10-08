(function() {

	'use strict';

	angular
		.module('iserveu')
		.service('RoleService', RoleService);


	function RoleService(role, $rootScope, $mdToast, ToastMessage) {

		var vm = this;

		vm.grant_role = grant_role;
		vm.delete_role = delete_role;
		vm.check_new_role = check_new_role;

		function grant_role(role_name, user_id){
			role.grantRole({
				user_id: user_id,
				role_name: role_name}).then(function(){
				$rootScope.$emit('refreshLocalStorageSettings');
			});
		}

		function delete_role(role_id, user_id){
			role.deleteUserRole({
				user_id: user_id,
				role_id: role_id}).then(function(){
				$rootScope.$emit('refreshLocalStorageSettings');
			});
		}

		vm.check_roles = function(roles, this_users_roles) {
			angular.forEach(roles, function(role, key){
				role["this_users_role"] = false;
				angular.forEach(this_users_roles, function(this_role, key){
					if(role.display_name == this_role){
						console.log('foo');
						role["this_users_role"] = true;
					}
				})
			})
		}

		function check_new_role(role, id){
			if(id == JSON.parse(localStorage.getItem('user')).id){
				var toast = ToastMessage.action("Are you sure you want to modify your own role?", "Yes");
				$mdToast.show(toast).then(function(response){
					if(response == 'ok'){
						editMyOwnRole(role, id);
					}
				})
			}
			else {
				editUserRole(role, id);
			}
		}

		function editMyOwnRole(role, my_id){
			if(role.this_users_role){
				grant_role(role.name, my_id);
			}
			else if (!role.this_users_role){
				delete_role(role.id, my_id);
			}
		}

		function editUserRole(role, user_id){
			if(!role.this_users_role){
				grant_role(role.name, user_id);
			}
			else if (role.this_users_role){
				delete_role(role.id, user_id);
			}
		}



	}



}());
