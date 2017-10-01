(function() {
	
	angular
		.module('iserveu')
		.directive('motionSearch', ['$mdMedia', '$mdSidenav', 'motionSearchFactory', motionSearch]);

	function motionSearch($mdMedia, $mdSidenav, motionSearchFactory) {

		var sidebarTemplate;

		return {
			controller: ['$scope', function($scope) {
				$scope.search = motionSearchFactory;
			}],
			link: function(scope, el, attrs) {
				scope.getContent = function() {
				    return $mdMedia('gt-sm') ?
				    'app/components/motionSearchbar/motionSearchbar_toolbar.tpl.html' :
					'app/components/motionSearchbar/motionSearchbar.tpl.html';
				}
			},
			template: '<div ng-include="getContent()"></div>'
		}



	}

})();