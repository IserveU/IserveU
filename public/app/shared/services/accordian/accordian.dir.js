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
			templateUrl: 'app/shared/services/accordian/accordian.tpl.html'
		}


	}

})();