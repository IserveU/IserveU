(function() {

	'use strict';

	angular
		.module('iserveu')
		.service('GrantRoleService', GrantRoleService);


	function GrantRoleService(role) {

		var vm = this;

		vm.roles;

		function getRoles(){
			role.getRoles().then(function(results){
				vm.roles = results;
			}, function(error){
				console.log(error);
			})
		}

		vm.grant = function(role_name, user_id){
			var data = {
				user_id: user_id,
				role_name: role_name
			}

			role.grantRole(data).then(function(results){
				
			}, function(error){
				console.log(error);
			})
		}


		getRoles();
	}



}());
