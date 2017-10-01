(function() {


	'use strict';

	angular
		.module('iserveu')
		.directive('editPageContent', [
		'$state', '$stateParams', 'ToastMessage', 'pageService',
			editPageContent]);

  	 /** @ngInject */
	function editPageContent($state, $stateParams, ToastMessage, pageService) {


		function editPageController() {

			this.pageService = pageService;
			this.saveString = "Save";

			this.save = function() {
				pageService.processing = true;
				pageService.update($stateParams.id, {
					'title': this.pageService.title,
					'text': this.pageService.text
				});
			};

			this.cancel = function() {
        ToastMessage.cancelChanges(function(){
        	$state.go('pages', {id: $stateParams.id});
        });
			};

			pageService.initLoad($stateParams.id);
		}


		return {
			controller: editPageController,
			controllerAs: 'edit',
			templateUrl: 'app/components/pages/edit-page.tpl.html'
		}

	}


})();