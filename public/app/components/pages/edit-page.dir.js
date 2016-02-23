(function() {


	'use strict';

	angular
		.module('iserveu')
		.directive('editPageContent', editPageContent);

	function editPageContent($state, $stateParams, ToastMessage, pageObj, dropHandler) {


		function editPageController() {

			this.pageObj = pageObj;
			this.dropHandler = dropHandler;
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
					   '<text-angular ng-model="edit.pageObj.content" ta-file-drop="edit.dropHandler"></text-angular><div layout="row">',
					   '<spinner name="edit.saveString" ng-click="edit.save()" on-hide="edit.pageObj.processing"></spinner>',
					   '<md-button ng-click="edit.cancel()">Cancel</md-button></div>',
					   '</md-card-content></md-card>'].join('')
		}

	}


})();