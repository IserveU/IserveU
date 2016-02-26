(function() {


	'use strict';

	angular
		.module('iserveu')
		.directive('userRole', userRole);

	function userRole() {




		return {
			templateUrl: 'app/components/user/components/role/role.tpl.html'
		}



	}


})();