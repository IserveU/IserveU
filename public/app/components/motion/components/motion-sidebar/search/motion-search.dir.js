(function() {
	
	angular
		.module('iserveu')
		.directive('motionSearch', ['$mdMedia', '$mdSidenav', 'motionSearchFactory', motionSearch]);

     /** @ngInject */
	function motionSearch($mdMedia, $mdSidenav, motionSearchFactory) {

		var sidebarTemplate;

		return {
			controller: ['$scope', function($scope) {
				$scope.search = motionSearchFactory;
			}],
			link: function(scope, el, attrs) {
				scope.getContent = function() {
				    return $mdMedia('gt-sm') ?
				    'app/components/motion/components/motion-sidebar/search/motion-toolbar-search.tpl.html' :
					'app/components/motion/components/motion-sidebar/search/motion-search.tpl.html';
				}
			},
			template: '<div ng-include="getContent()"></div>'
		}



	}

})();