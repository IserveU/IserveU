(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.directive('searchFilter', searchFilter);

	function searchFilter($filter) {


		function controllerMethod(){


		}

		function linkMethod(scope, element, attr, ctrl){

		}

		return {
			controller: controllerMethod,
			controllerAs: 'search',
			bindToController: true, 
			templateUrl: 'app/shared/customdirectives/searchfilter/searchfilter.tpl.html'			
		}
	}

}());

