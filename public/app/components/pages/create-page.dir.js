(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('createPageContent', 
			['$state',
			 'pageService',
			 'ToastMessage',
		createPage]);

	function createPage($state, pageService, ToastMessage) {

		function createPageController() {

			this.service = pageService;
			this.save    = save;
			this.cancel  = cancel;

			function save(data) {
				pageService.processing = true;
				pageService.save(data);
			}

			function cancel() {
				ToastMessage.cancelChanges(function(){
					$state.go('dashboard');
				});
			};
		};

		return {
			controller: createPageController,
			controllerAs: 'create',
			templateUrl: 'app/components/pages/create-page.tpl.html'
		}
	}

})();