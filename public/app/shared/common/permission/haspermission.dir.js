(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.directive('hasPermission', hasPermission);

  	 /** @ngInject */
	function hasPermission(SetPermissionsService, $state) {

		function linkMethod(scope, element, attrs) {
			var redirect = false;
			if(attrs.hasPermission.substring(0,16) == "redirectIfCannot"){
				attrs.hasPermission = attrs.hasPermission.substring(16);
				redirect = true;
			}
			if(attrs.hasPermission.substring(0,6) == 'hasAll'){	//when the permission attribute begins with 'hasAll'
				if(!SetPermissionsService.canAll(attrs.hasPermission.substring(6))){ //passes in the next part after, hasAll[ 'type' ]
					element.remove(attrs.hasPermission);
				}
			}
			else if(!SetPermissionsService.can(attrs.hasPermission)){ 
				element.remove(attrs.hasPermission);
				if(redirect){
					$state.go('permissionfail');
				}
			}
		}

		return {
			restrict: 'AE',
			link: linkMethod
		}

	}

}());