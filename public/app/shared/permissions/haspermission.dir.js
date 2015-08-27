(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.directive('hasPermission', hasPermission);

	function hasPermission(SetPermissionsService) {

		function linkMethod(scope, element, attrs) {
			if(attrs.hasPermission.substring(0,6) == 'hasAll'){	//when the permission attribute begins with 'hasAll'
				if(!SetPermissionsService.canAll(attrs.hasPermission.substring(6))){ //passes in the next part after, hasAll[ 'type' ]
					element.remove(attrs.hasPermission);
				}
			}
			//when html prepends permission with '!', ie. !show-users; it will show the element
			else if(!SetPermissionsService.can(attrs.hasPermission) && attrs.hasPermission.substring(0,1) != "!"){ 
				element.remove(attrs.hasPermission);
			}
		}

		return {
			restrict: 'E',
			link: linkMethod,
		}

	}

}());