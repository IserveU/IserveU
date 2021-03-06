(function() {

	angular
		.module('iserveu')
		.directive('userSearch', ['$mdMedia', '$mdSidenav', 'userSearchFactory', userSearch]);

	function userSearch($mdMedia, $mdSidenav, userSearchFactory) {
		return {
			controller: ['$scope', function($scope) {
				$scope.search = userSearchFactory;
			}],
			link: function(scope, el, attrs) {
				scope.getContent = function() {
				    return 'app/components/userSidebar/search/userSearchbar.tpl.html';
				}
			},
			template: '<div ng-include="getContent()"></div>'
		}



	}

})();
