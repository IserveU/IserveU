(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.directive('showFooter', footer);

	function footer() {
	
		return {
			templateUrl: 'app/components/nav/footer/footer.tpl.html'	 // using templateUrl because template does not allow for another directive controller

		}

	}

}());