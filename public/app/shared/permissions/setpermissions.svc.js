(function() {

	'use strict';

	angular
		.module('iserveu')
		.service('SetPermissionsService', SetPermissionsService);


	// This is a TODO!
  	 /** @ngInject */
	function SetPermissionsService($rootScope, $state, auth) {

		var vm = this;

		vm.set = set;
		vm.permissions;

		function set(permissions_array){
			if(permissions_array == undefined){auth.logout();}
			localStorage.setItem('permissions', permissions_array);
			vm.permissions = JSON.parse(permissions_array);
		}


		vm.can = function(action){
			var result = false;
			angular.forEach(vm.permissions, function(value, key){
				if(value == action){
					result = true;
				}
			});
			return result;
		}

		vm.canAll = function(section_name){
			var iterator = 0;
			var result = false;

			angular.forEach(vm.permissions, function(value, key){
				var splitpermissions = value.split('-');
				if(splitpermissions[1] == section_name){
					iterator++;
				}
			});

			if(iterator > 1){
				result = true;
			}
			
			return result;
		}

		if(!vm.permissions){set(localStorage.getItem('permissions'));}
		

	}
})();