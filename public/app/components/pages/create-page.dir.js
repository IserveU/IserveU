(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('createPageContent', [
			'$state', 'pageObj', 'ToastMessage',
			createPage]);

  	/** @ngInject */
	function createPage($state, pageObj, ToastMessage) {

		function createPageController() {

			this.pageObj = pageObj;

			this.cancel = function() {
				ToastMessage.cancelChanges(function(){
					$state.go('dashboard');
				});
			};
		};


		return {
			controller: createPageController,
			controllerAs: 'create',
			template: ['<md-card><md-card-content><form name="page" ng-submit="page.$valid && create.pageObj.save(create.form)">',
					   '<md-input-container style="width: 100%">',
					   '<input placeholder="Page title" ng-model="create.form.title" required/></md-input-container>',
					   '<text-angular ng-model="create.form.content" ta-file-drop="taDropHandler"></text-angular>',
					   '<div layout="row"><spinner name="\'Create\'" on-hide="create.pageObj.processing"></spinner>',
					   '<md-button ng-click="create.cancel()">Cancel</md-button></div>',
					   '</md-card-content></md-card>'].join('')
		}



	}

})();