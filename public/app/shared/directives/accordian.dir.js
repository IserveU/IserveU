(function() {
	
	angular
		.module('iserveu')
		.directive('isuAccordian', isuAccordian);

	function isuAccordian() {

		return {
			transclude: true,
			scope: {
				'icon': '=',
				'title': '=',
				'isOpen': '='
			},
			template: ['<div class="accordian" flex ng-click="isOpen = !isOpen" ng-class="isOpen?\'opened\':\'closed\'">',
					   '<md-icon class="mdi {{icon}} title-icon"></md-icon>',
					   '<h3 class="md-body-2" >{{title}}</h3>',
					   '<md-icon ng-class="isOpen?\'mdi mdi-menu-up\':\'mdi mdi-menu-up go-up\'"></md-icon>',
					   '</div><div ng-transclude></div>'].join('')
		}


	}

})();