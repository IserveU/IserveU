(function() {

	'use strict';
	
	angular
		.module('iserveu')
		.directive('crowdVerification', ['crowdVerificationResource', crowdVerification]);

	function crowdVerification(crowdVerificationResource) {

		function crowdVerificationController() {
			


		}


		return {
			controller: crowdVerificationController,
			controllerAs: 'crowdVerification',
			templateUrl: 'app/components/crowdVerification/crowdVerification.tpl.html'
		}


	}

})();