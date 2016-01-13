(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('UserRoleController', UserRoleController);

	function UserRoleController(RoleService, role){

		var vm = this;

		vm.roles;
		vm.this_users_roles 	= [];
	    vm.checkRoles 			= checkRoles;
	    vm.edit_role = false;

		vm.uponPressingBack = function(){
			console.log('foo');
			getUser($stateParams.id);
		}

		function getAllRoles(){
			console.log('getAllRoles');
			role.getRoles().then(function(results){
				vm.roles = results;
			});
		}

		function checkRoles(){
			console.log('checkRoles');
			RoleService.check_roles(vm.roles, vm.this_users_roles);
		}

		getAllRoles();

	}

})();