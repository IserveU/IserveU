(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('createPageContent', createPage);

  	/** @ngInject */
	function createPage($state, pageObj, ToastMessage, dropHandler) {

		function createPageController() {

			this.dropHandler = dropHandler;
			this.pageObj = pageObj;
			this.saveString = "Create";

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
					   '<text-angular ng-model="create.form.content" ta-file-drop="create.dropHandler"></text-angular>',
					   '<div layout="row"><spinner name="create.saveString" on-hide="create.pageObj.processing"></spinner>',
					   '<md-button ng-click="create.cancel()">Cancel</md-button></div>',
					   '</md-card-content></md-card>'].join('')
		}



	}

})();