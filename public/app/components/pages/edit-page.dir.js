(function() {


	'use strict';

	angular
		.module('iserveu')
		.directive('editPageContent', [
		'$state', '$stateParams', 'ToastMessage', 'pageObj',
			editPageContent]);

  	 /** @ngInject */
	function editPageContent($state, $stateParams, ToastMessage, pageObj) {


		function editPageController() {

			this.pageObj = pageObj;
			this.saveString = "Save";

			this.save = function() {
				pageObj.processing = true;
				pageObj.update($stateParams.id, {
					'title': this.pageObj.title,
					'content': this.pageObj.content
				});
			};

			this.cancel = function() {
	            ToastMessage.cancelChanges(function(){
	            	$state.go('pages', {id: $stateParams.id});
	            });
			};

			pageObj.initLoad($stateParams.id);
		}


		return {
			controller: editPageController,
			controllerAs: 'edit',
			template: ['<md-card><md-card-content><md-input-container style="width: 100%; margin-bottom: 0">',
					   '<input ng-model="edit.pageObj.title"/></md-input-container>',
					   '<textarea alloy-editor id="create-page-editor" ng-model="edit.pageObj.content"></textarea><div layout="row">',
					   '<spinner name="edit.saveString" ng-click="edit.save()" on-hide="edit.pageObj.processing"></spinner>',
					   '<md-button ng-click="edit.cancel()">Cancel</md-button></div>',
					   '</md-card-content></md-card>'].join('')
		}

	}


})();