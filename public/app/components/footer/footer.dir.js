(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.directive('showFooter', footer);

	function footer() {
	
		return {
			templateUrl: 'app/components/footer/footer.tpl.html'
		}

	}

}());