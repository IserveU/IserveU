(function() {

	'use strict';

	angular
		.module('iserveu')
		.service('RoleService', RoleService);


	function RoleService($mdToast, $stateParams, role, auth, ToastMessage) {

		var vm = this;

		vm.grant_role = grant_role;
		vm.delete_role = delete_role;
		vm.check_new_role = check_new_role;

		function grant_role(role_name, user_id){
			role.grantRole({
				user_id: user_id,
				role_name: role_name}).then(function(){
					refreshLocalStorageSettings();
			});
		}

		function delete_role(role_id, user_id){
			role.deleteUserRole({
				user_id: user_id,
				role_id: role_id}).then(function(){
					refreshLocalStorageSettings();
			});
		}

		function refreshLocalStorageSettings(){

			if($stateParams.id == JSON.parse(localStorage.getItem('user')).id){
					auth.getSettings().then(function(result){
					localStorage.removeItem('user');
					localStorage.removeItem('permissions');
					localStorage.setItem('user', JSON.stringify(result.data.user));
					localStorage.setItem('permissions', JSON.stringify(result.data.user.permissions));
				})
			}

		}

		vm.check_roles = function(roles, this_users_roles) {
			angular.forEach(roles, function(role, key){
				role["this_users_role"] = false;
				angular.forEach(this_users_roles, function(this_role, key){
					if(role.display_name == this_role){
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
