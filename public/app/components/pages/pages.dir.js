(function() {


	'use strict';

	angular
		.module('iserveu')
		.directive('pageContent', ['$stateParams', 'pageObj', 'UserbarService', pageContent]);

  	 /** @ngInject */
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
			template: ['<pages-fab></pages-fab><section layout-margin><md-card ng-class="p.pageObj.pageLoading ? p.loading : none " flex><md-card-content>',
					   '<p ng-bind-html="p.pageObj.content" layout-padding></p></md-card-content></md-card></section>'].join('')
		}

	}


})();