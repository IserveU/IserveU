(function() {

	'use strict';
	
	angular
		.module('iserveu')
		.directive('emailManager', 
			['settings',
		emailManager]);

	function emailManager(settings) {

		function emailManagerController() {
			this.service = settings;
			this.settings = settings.getData();
			console.log(settings);
		}


		return {
			restrict: 'EA',
			controller: emailManagerController,
			controllerAs: 'email',
			templateUrl: 'app/components/admin.dash/email/email.tpl.html'
		}

	}

})();