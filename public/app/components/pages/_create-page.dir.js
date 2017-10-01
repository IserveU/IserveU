// (function() {

// 	'use strict';

// 	angular
// 		.module('iserveu')
// 		.directive('createPageContent',
// 			['$state',
// 			 '$stateParams',
// 			 'pageService',
// 			 'ToastMessage',
// 		createPage]);

// 	function createPage($state, $stateParams, pageService, ToastMessage) {

// 		function createPageController() {

// 			this.service = pageService;
// 			this.save    = save;
// 			this.cancel  = cancel;

// 			function save(data) {
// 				pageService.processing = true;
// 				pageService.save(data);
// 			}

// 			function cancel() {
// 				ToastMessage.cancelChanges(function(){
// 					$state.go('dashboard');
// 				});
// 			};
// 		}

// 		function createPageLink(scope, el, attrs) {

// 			var autopost = attrs.autopost || angular.element(el).attr('autopost');

// 			if(autopost) {
// 				pageService.create(data).then(function(res){
// 					var body = res.data || res;
// 					scope.page = body;
// 					$stateParams.id = body.id;
// 				});
// 			}
// 		}

// 		return {
// 			controller: createPageController,
// 			controllerAs: 'create',
// 			templateUrl: 'app/components/pages/create-page.tpl.html'
// 		}
// 	}

// })();