(function() {


	'use strict';

	angular
		.module('iserveu')
		.directive('pageContent', pageContent);

	function pageContent($stateParams, pageObj, UserbarService) {


		function pageController() {

			this.pageObj = pageObj;

			this.loading = "loading";

			pageObj.initLoad($stateParams.id);

			UserbarService.title = pageObj.title;

		}


		return {
			controller: pageController,
			controllerAs: 'p',
			template: '<md-card ng-class="p.pageObj.pageLoading ? p.loading : none "><md-card-content><p ng-bind-html="p.pageObj.content"></p></md-card-content></md-card>'
		}

	}


})();