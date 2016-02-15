(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.directive('showFooter', footer);

	function footer() {

		
		return {
			templateUrl: 'app/shared/nav/footer/footer.tpl.html'
		}

	}

}());