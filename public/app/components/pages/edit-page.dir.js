(function() {


	'use strict';

	angular
		.module('iserveu')
		.directive('editPageContent', editPageContent);

	function editPageContent($stateParams, pageObj) {


		function editPageController() {

			this.pageObj = pageObj;

			pageObj.initLoad($stateParams.id);


			this.saveChanges = function() {

				var data = {
					'title': this.pageObj.title,
					'content': this.pageObj.content
				}

				pageObj.update($stateParams.id, data);

			}

		}


		return {
			controller: editPageController,
			controllerAs: 'edit',
			template: '<md-card><md-card-content><input ng-model="edit.pageObj.title"/><text-angular ng-model="edit.pageObj.content"></text-angular><md-button ng-click="edit.saveChanges()">Save</md-button></md-card-content></md-card>'
		}

	}


})();