(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('sidebar', ['$compile', sidebar]);

  	 /** @ngInject */
	function sidebar($compile) {

		function sidebarController() {

			// unused currently, since htere is only one sidebar

			// var vm = this;

			// $rootScope.$mdMedia = $mdMedia;
			// vm.keepOpen = false;

			// vm.toggleSidenav = function(menuId){
			// 	$mdSidenav(menuId).toggle().then(function(){
			// 		vm.keepOpen = !$rootScope.keepOpen;
			// 	});
			// }

			// vm.closeSidenav = function(menuId){
			// 	$mdSidenav(menuId).close().then(function(){
			// 		vm.keepOpen = false;
			// 	});
			// }
		}




		function linkMethod(scope, element, attrs) {

			scope.$watch('currentState', function() {
				angular
					.element(document.getElementById('sidebar-inner'))
					.empty()
					.append($compile("<div class='" + attrs.sidebar + "-sidebar'" + attrs.sidebar + "-sidebar></div>")(scope));
			});
		}


		return {
			restrict: 'E',
			link: linkMethod
		}

	}

}());

